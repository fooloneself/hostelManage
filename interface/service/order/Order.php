<?php
namespace service\order;
use common\components\ErrorManager;
use common\components\Server;
use common\models\MerchantMember;
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
            if(empty($r)){
                return false;
            }else if(!$r->canPlaceOrder($room['start'],$room['end'])){
                return false;
            }
            if($room['type']==OrderRoom::TYPE_CLOCK){
                $bill=$r->generateHoursBill($room['start'],$room['end'],$totalAmount);
            }else{
                $bill=$r->generateDaysBill($room['start'],$room['end'],$totalAmount);
            }
            $orderRooms[]=$r;
            $total+=$bill->getTotalAmount();
        }
        $pay=PayBill::byOrder($this);
        $payingAmount=$pay->pay($this->paying)->getPayingAmount();
        if(!$this->addOrder($total,$payingAmount,1,$totalAmount>=0)){
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
     * @param $startTime
     * @param $endTime
     * @param array $guests
     * @param int $totalAmount
     * @return bool
     */
    public function occupancy($roomId,$type,$startTime,$endTime,array $guests,$totalAmount=-1){
        if(empty($this->guest)){
            $this->setError(ErrorManager::ERROR_NO_GUEST_INFO);
            return false;
        }
        $r=Room::byId($this->merchant,$roomId);
        if(empty($r)){
            $this->setError(ErrorManager::ERROR_ROOM_NOT_EXISTS);
            return false;
        }else if(!$r->canPlaceOrder($startTime,$endTime)){
            $this->setError($r->getError());
            return false;
        }
        if($type==OrderRoom::TYPE_CLOCK){
            $bill=$r->generateHoursBill($startTime,$endTime,$totalAmount);
        }else{
            $bill=$r->generateDaysBill($startTime,$endTime,$totalAmount);
        }
        $pay=PayBill::byOrder($this);
        $payingAmount=$pay->pay($this->paying)->getPayingAmount();
        if(!$this->addOrder($bill->getTotalAmount(),$payingAmount,0,$totalAmount>=0)){
            $this->setError(ErrorManager::ERROR_ORDER_CREATE_FAIL);
            return false;
        }
        if(!$r->occupancy($this,$guests)){
            $this->setError($r->getError());
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
     * @param $paidAmount
     * @param int $isReverse
     * @param int $isTemporary
     * @return bool
     */
    protected function addOrder($totalAmount,$paidAmount,$isReverse=0,$isTemporary=0){
        $this->_order->amount_payable=$totalAmount;
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
     * 入住房间
     * @param Room $room
     * @return bool|false|int
     */
    public function occupancyRoom(Room $room){
        if($this->isNew){
            $orderRoom=new OrderRoom();
            $orderRoom->order_id=$this->getId();
            $orderRoom->room_id=$room->getId();
            $orderRoom->start_time=$room->getBill()->getStartTime();
            $orderRoom->end_time=$room->getBill()->getEndTime();
            $orderRoom->status=OrderRoom::STATUS_OCCUPANCY;
            $orderRoom->amount=$room->getBill()->getTotalAmount();
            $orderRoom->type=$room->getBill()->getType();
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

    /**
     * 退房
     * @param Room $room
     * @return bool|false|int
     */
    public function checkOutRoom(Room $room){
        $orderRoom=OrderRoom::findOne(['order_id'=>$this->getId(),'room_id'=>$room->getId()]);
        if(empty($orderRoom)){
            return false;
        }
        $orderRoom->status=OrderRoom::STATUS_CHECK_OUT;
        $orderRoom->end_time=$_SERVER['REQUEST_TIME'];
        return $orderRoom->update(false);
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
}