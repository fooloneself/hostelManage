<?php
namespace service\order;
use common\components\ErrorManager;
use common\components\Server;
use common\models\MerchantMember;
use common\models\OrderCostDetail;
use common\models\OrderRoom;
use service\guest\Guest;
use service\merchant\Merchant;


class Order extends Server{

    private $_order;
    protected $merchant;
    protected $guest;
    protected $paying=[];
    protected $isNew;
    public function __construct(Merchant $merchant,\common\models\Order $order,$isNew=true)
    {
        $this->_order=$order;
        $this->merchant=$merchant;
        $this->isNew=$isNew;
    }

    /**
     * 通过订单ID实例化
     * @param Merchant $merchant
     * @param $orderId
     * @return null|static
     */
    public static function byId(Merchant $merchant,$orderId){
        $order=\common\models\Order::findOne(['id'=>$orderId,'mch_id'=>$merchant->getId()]);
        if(empty($order)){
            return null;
        }else{
            return new static($merchant,$order,false);
        }
    }

    /**
     * 通过订单编号实例化
     * @param Merchant $merchant
     * @param $orderNo
     * @return null|static
     */
    public function byNo(Merchant $merchant,$orderNo){
        $order=\common\models\Order::findOne(['order_no'=>$orderNo,'mch_id'=>$merchant->getId()]);
        if(empty($order)){
            return null;
        }else{
            return new static($merchant,$order,false);
        }
    }

    /**
     * 新建订单
     * @param Merchant $merchant
     * @return static
     */
    public static function newOne(Merchant $merchant){
        $order=new \common\models\Order();
        $order->order_no=self::makeOrderNo($merchant->getId());
        $order->mch_id=$merchant->getId();
        return new static($merchant,$order);
    }

    /**
     * 生成订单号
     * @param $merchantId
     * @return string
     */
    protected static function makeOrderNo($merchantId){
        return 'A'.str_pad(base_convert($merchantId,10,16),STR_PAD_LEFT,6).date('ymdHmi').rand(100,999);
    }

    /**
     * 获取订单ID
     * @return int
     */
    public function getId(){
        return intval($this->_order->getAttribute('id'));
    }

    /**
     * 获取订单号
     * @return mixed
     */
    public function getOrderNo(){
        return $this->_order->getAttribute('order_no');
    }

    /**
     * 下单人
     * @param $mobile
     * @param $name
     * @return $this
     */
    public function byGuest($mobile,$name){
        $this->guest=Guest::by($this->merchant,$mobile,$name);
        return $this;
    }

    /**
     * 订单来源
     * @param $channel
     * @return $this
     */
    public function from($channel){
        $this->_order->channel=$channel;
        return $this;
    }

    /**
     * 备注
     * @param $mark
     * @return $this
     */
    public function mark($mark){
        $this->_order->mark=$mark;
        return $this;
    }

    /**
     * 支付
     * @param array $pays
     * @return $this
     */
    public function pay(array $pays){
        $this->paying=$pays;
        return $this;
    }

    /**
     * 预定
     * @param array $rooms
     * @param int $totalAmount
     * @return bool
     */
    public function reverse(array $rooms,$totalAmount=-1){
        if(empty($this->guest)){
            return false;
        }
        $orderRooms=[];
        $total=0;
        foreach ($rooms as $room){
            $r=Room::byId($this->merchant,$room['roomId']);
            $bill=$r->newHoursBill($room['quantity']);
            if(empty($r)){
                return false;
            }else if(!$r->canPlaceOrder($room['start'],$room['end'])){
                return false;
            }
            $bill=$this->generateRoomBill($r,$room['type'],$room['start'],$room['end'],$totalAmount);
            $orderRooms[]=$r;
            $total+=$bill->getTotalAmount();
        }
        $pay=PayBill::byOrder($this);
        $payingAmount=$pay->pay($this->paying)->getPayingAmount();
        $isTemporary=$totalAmount>=0?1:0;
        if(!$this->addOrder($total,$payingAmount,1,$isTemporary)){
            return false;
        }
        foreach ($orderRooms as $orderRoom){
            if(!$this->reverseRoom($orderRoom)){
                return false;
            }
        }
        if(!$pay->insert()){
            return false;
        }
        return true;
    }

    /**
     * 单房间下单入住
     * @param $roomId
     * @param $type
     * @param $quantity
     * @param array $guests
     * @param int $totalAmount
     * @return bool
     */
    public function occupancy($roomId,$type,$quantity,array $guests,$totalAmount=-1){
        if(empty($this->guest)){
            $this->setError(ErrorManager::ERROR_NO_GUEST_INFO);
            return false;
        }
        $r=Room::byId($this->merchant,$roomId);
        $bill=$r->newBill();
        if($type==OrderRoom::TYPE_DAY){
            $bill->days($quantity);
        }else{
            $bill->hours($quantity);
        }
        if(empty($r)){
            $this->setError(ErrorManager::ERROR_ROOM_NOT_EXISTS);
            return false;
        }else if(!$r->canPlaceOrder($bill->getStartTime(),$bill->getEndTime())){
            $this->setError($r->getError());
            return false;
        }
        if($type==OrderRoom::TYPE_DAY){
            $bill->generate($totalAmount);
        }else{
            $bill->generate($totalAmount);
        }
        $pay=PayBill::byOrder($this);
        $payingAmount=$pay->pay($this->paying)->getPayingAmount();
        $isTemporary=$totalAmount>=0?1:0;
        if(!$this->addOrder($bill->getTotalAmount(),$bill->getTotalAmount(),$payingAmount,0,$isTemporary)){
            $this->setError(ErrorManager::ERROR_ORDER_CREATE_FAIL,json_encode($this->_order->getErrors()));
            return false;
        }
        if(!$r->occupancy($this,$quantity,$guests,true)){
            $this->setError($r->getError());
            return false;
        }
        if(!$bill->insert($this)){
            $this->setError($bill->getError());
            return false;
        }
        if(!$pay->insert()){
            $this->setError(ErrorManager::ERROR_ORDER_PAY_RECORD_FAIL);
            return false;
        }
        return true;
    }

    /**
     * 入住预定的房间
     * @param $roomId
     * @param array $guest
     * @return bool
     */
    public function occupancyPredetermined($roomId,array $guest){
        $r=Room::byId($this->merchant,$roomId);
        if(empty($r)){
            return false;
        }
        if(empty($r)){
            return false;
        }else if(!$this->occupancyRoom($r,$guest)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 新增订单
     * @param $totalAmount
     * @param $payableAmount
     * @param $paidAmount
     * @param int $isReverse
     * @param int $isTemporary
     * @return bool
     */
    protected function addOrder($totalAmount,$payableAmount,$paidAmount,$isReverse=0,$isTemporary=0){
        $this->_order->amount=$totalAmount;
        $this->_order->amount_payable=$payableAmount;
        $this->_order->is_reverse=$isReverse?\common\models\Order::REVERSE_YES:\common\models\Order::REVERSE_NO;
        $this->_order->amount_paid=$paidAmount;
        $this->_order->place_time=$_SERVER['REQUEST_TIME'];
        $this->_order->guest_id=$this->guest->getId();
        $this->_order->status=\common\models\Order::STATUS_NORMAL;
        $this->_order->is_settlement=\common\models\Order::SETTLE_NO;
        $this->_order->amount_deffer=$totalAmount-$paidAmount;
        $this->_order->is_temporary=$isTemporary;
        return $this->_order->insert();
    }

    /**
     * 修改订单
     * @return false|int
     */
    protected function updateOrder(){
        return $this->_order->update();
    }

    /**
     * 入住房间
     * @param Room $room
     * @param $quantity
     * @param $isNew
     * @return bool
     */
    public function occupancyRoom(Room $room,$quantity,$isNew){
        if($isNew){
            $orderRoom=new OrderRoom();
            $orderRoom->order_id=$this->getId();
            $orderRoom->room_id=$room->getId();
            $orderRoom->start_time=$room->getBill()->getStartTime();
            $orderRoom->end_time=$room->getBill()->getEndTime();
            $orderRoom->status=OrderRoom::STATUS_OCCUPANCY;
            $orderRoom->amount=$room->getBill()->getTotalAmount();
            $orderRoom->type=$room->getBill()->getType();
            $orderRoom->quantity=$quantity;
            if($orderRoom->insert(false)){
                return true;
            }else{
                $this->setError(ErrorManager::ERROR_ROOM_PLACE_ADD_FAIL);
                return false;
            }
        }else{
            $orderRoom=OrderRoom::findOne(['order_id'=>$this->getId(),'room_id'=>$room->getId()]);
            if($orderRoom){
                $orderRoom->status=OrderRoom::STATUS_OCCUPANCY;
                $orderRoom->start_time=$_SERVER['REQUEST_TIME'];
                if($orderRoom->update(false)){
                    return true;
                }else{
                    $this->setError(ErrorManager::ERROR_ROOM_PLACE_UPDATE_FAIL);
                    return false;
                }
            }else{
                $this->setError(ErrorManager::ERROR_ORDER_ROOM_UN_PLACE);
                return false;
            }
        }
    }

    /**
     * 预定房间
     * @param Room $room
     * @return bool
     */
    public function reverseRoom(Room $room){
        $orderRoom=new OrderRoom();
        $orderRoom->order_id=$this->getId();
        $orderRoom->room_id=$room->getId();
        $orderRoom->start_time=$room->getBill()->getStartTime();
        $orderRoom->end_time=$room->getBill()->getEndTime();
        $orderRoom->status=OrderRoom::STATUS_REVERSE;
        $orderRoom->amount=$room->getBill()->getTotalAmount();
        $orderRoom->type=$room->getBill()->getType();
        return $orderRoom->insert(false);
    }

    public function checkOutRoom(Room $room){
        $orderRoom=OrderRoom::findOne(['order_id'=>$this->getId(),'room_id'=>$room->getId()]);
        if(empty($orderRoom)){
            $this->setError(ErrorManager::ERROR_ORDER_ROOM_UN_PLACE);
            return false;
        }
        $orderRoom->status=OrderRoom::STATUS_CHECK_OUT;
        $orderRoom->end_time=$_SERVER['REQUEST_TIME'];
        if($orderRoom->update(false)){
            return true;
        }else{
            $this->setError(ErrorManager::ERROR_ORDER_ROOM_CHANGE_FAIL);
            return false;
        }
    }
    /**
     * 退房
     * @param Room $room
     * @return bool|false|int
     */
    public function checkOut(Room $room){
        if(!$room->checkOut($this)){
            $this->setError($room->getError());
            return false;
        }
        $pay=PayBill::byOrder($this)->pay($this->paying);
        $this->_order->amount_paid+=$pay->getPayingAmount();
        $this->_order->amount_deffer-=$pay->getPayingAmount();
        $this->_order->is_settlement=\common\models\Order::SETTLE_YES;
        if($this->_order->amount_deffer==0){
            $this->_order->status=\common\models\Order::STATUS_NORMAL;
        }else{
            $this->_order->status=\common\models\Order::STATUS_ABNORMAL;
            $this->_order->abnormal_type=\common\models\Order::ABNORMAL_DEFFER;
        }
        if(!$this->_order->update(false)){
            $this->setError(ErrorManager::ERROR_ORDER_STATUS_CHANGE_FAIL);
            return false;
        }else if(!$pay->insert()){
            $this->setError(ErrorManager::ERROR_ORDER_PAY_RECORD_FAIL);
            return false;
        }else{
            return true;
        }
    }

    public function continueRoom(Room $room,$type,$quantity,$totalAmount){

    }

    /**
     * 获取下单客户
     * @return Guest
     */
    public function getGuest(){
        if(empty($this->guest)){
            $member=MerchantMember::findOne(['mch_id'=>$this->merchant->getId(),'id'=>$this->_order->guest_id]);
            $this->guest=new Guest($member);
        }
        return $this->guest;
    }

    /**
     * 换房间
     * @param Room $fromRoom
     * @param Room $toRoom
     * @param $start
     * @param $totalAmount
     * @return bool
     */
    public function changeRoom(Room $fromRoom,Room $toRoom,$start,$totalAmount){
        $start=intval(date('Ymd',$start));
        $count=OrderCostDetail::find()->where([
            'and',
            ['order_id'=>$this->getId()],
            ['room_id'=>$fromRoom->getId()],
            ['>=','date',$start]
        ])->count();
        if($count<=0){
            $this->setError(ErrorManager::ERROR_ORDER_ROOM_NO_TIME);
            return false;
        }
        $bill=$toRoom->newBill()->days($count)->generate($totalAmount);
        if(!$fromRoom->checkOut($this)){
            $this->setError($fromRoom->getError());
            return false;
        }else if(!$toRoom->occupancy($this,$count,$fromRoom->getOccupancyGuest($this),true)){
            $this->setError($toRoom->getError());
            return false;
        }
        $pay=PayBill::byOrder($this)->pay($this->paying);
        if($totalAmount>=0){
            $this->_order->amount=$totalAmount;
            $this->_order->amount_payable=$totalAmount;
        }
        $this->_order->amount_paid+=$pay->getPayingAmount();
        $this->_order->amount_deffer-=$pay->getPayingAmount();
        if(!$this->updateOrder()){
            $this->setError(ErrorManager::ERROR_ORDER_ROOM_CHANGE_FAIL);
            return false;
        }else if(!$pay->insert()){
            $this->setError(ErrorManager::ERROR_ORDER_PAY_RECORD_FAIL);
            return false;
        }else if(!$this->deleteRoomCostRecord($fromRoom,$start)){
            return false;
        }else if(!$bill->insert($this)){
            $this->setError($bill->getError());
            return false;
        }else {
            return true;
        }
    }

    protected function deleteRoomCostRecord(Room $fromRoom,$start){
        $res=OrderCostDetail::deleteAll([
            'and',
            ['order_id'=>$this->getId()],
            ['room_id'=>$fromRoom->getId()],
            ['>=','date',$start]
        ]);
        if($res){
            return true;
        }else{
            $this->setError(ErrorManager::ERROR_ORDER_ROOM_CHANGE_FAIL);
            return false;
        }
    }
}