<?php
namespace service\order;
use common\components\ErrorManager;
use common\components\Server;
use common\models\OrderRoom;
use service\guest\Guest;
use service\merchant\Merchant;
use service\order\bill\OrderBill;
use service\order\activity\DiscountActivity;
use service\order\activity\FullCutActivity;
use service\order\activity\SpecialActivity;

class Order extends Server{
    protected $order;
    protected $merchant;
    protected $costBill;
    protected $payBill;
    protected $guest;
    public function __construct(Merchant $merchant,\common\models\Order $order)
    {
        $this->order=$order;
        $this->merchant=$merchant;
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
            return new static($merchant,$orderId);
        }
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
        return intval($this->order->id);
    }

    /**
     * 获取商户
     * @return Merchant
     */
    public function getMerchant(){
        return $this->merchant;
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
     * 获取订单人
     * @return \service\guest\Guest
     */
    public function getGuest(){
        return $this->guest;
    }
    /**
     * 订单支付清单
     * @param PayBill $payBill
     * @return $this
     */
    public function pay(PayBill $payBill){
        $this->payBill=$payBill;
        return $this;
    }

    /**
     * 修改
     * @return bool
     */
    protected function update(){
        if($this->order->update(false)){
            return true;
        }else{
            $this->setError(ErrorManager::ERROR_ORDER_UPDATE_FAIL);
            return false;
        }
    }

    /**
     * 新增
     * @return bool
     */
    protected function add(){
        if($this->order->insert(false)){
            return true;
        }else{
            $this->setError(ErrorManager::ERROR_ORDER_PLACE_FAIL);
            return false;
        }
    }

    /**
     * 修改订单中房间的状态
     * @param Room $room
     * @return bool
     */
    protected function checkOutRoom(Room $room){
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
        if(!$room->setDirty()){
            $this->setError($room->getError());
            return false;
        }else if(!$this->checkOutRoom($room)){
            return false;
        }
        if($this->payBill){
            $this->order->amount_paid+=$this->payBill->getPayingAmount();
            if(!$this->payBill->insert()){
                $this->setError(ErrorManager::ERROR_ORDER_PAY_RECORD_FAIL);
                return false;
            }
        }
        $this->order->amount_deffer=$this->order->amount_payable-$this->order->amount_paid;
        if($this->isSettle()){
            $this->_order->is_settlement=\common\models\Order::SETTLE_YES;
            if($this->_order->amount_deffer==0){
                $this->_order->status=\common\models\Order::STATUS_NORMAL;
            }else{
                $this->_order->status=\common\models\Order::STATUS_ABNORMAL;
                $this->_order->abnormal_type=\common\models\Order::ABNORMAL_DEFFER;
            }
        }
        if(!$this->update()){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 判断是否结单
     * @return bool
     */
    protected function isSettle(){
        $count=OrderRoom::find()->where([
            'order_id'=>$this->getId(),
            'status'=>[OrderRoom::STATUS_REVERSE,OrderRoom::STATUS_OCCUPANCY]
        ])->count();
        return $count==0;
    }

    /**
     * 添加消费金额
     * @param $amount
     */
    public function addAmount($amount){
        if(!$this->isTemporary()){
            $this->order->amount+=$amount;
            $this->order->amount_payable+=$amount;
        }
    }

    /**
     * 是否是临时定价
     * @return bool
     */
    protected function isTemporary(){
        return $this->order->is_temporary==1;
    }

    /**
     * 设置临时总价格
     * @param $amount
     */
    protected function setTemporaryAmount($amount){
        $this->order->amount=$amount;
        $this->order->amount_payable=$amount;
        $this->order->is_temporary=1;
    }
    /**
     * 新增应收费用
     * @param $amount
     */
    public function addPayableAmount($amount){
        if(!$this->isTemporary()){
            $this->order->amount_payable-=$amount;
        }
    }

    /**
     * 获取总消费金额
     * @return float
     */
    public function getAmount(){
        return floatval($this->order->amount);
    }

    /**
     * 获取活动
     * @param $activityId
     * @return bool|DiscountActivity|FullCutActivity|SpecialActivity
     */
    public function findActivity($activityId){
        $activity=\common\models\MerchantActivity::findOne(['id'=>$activityId,'mch_id'=>$this->merchant->getId()]);
        if(empty($activity)){
            $this->setError(ErrorManager::ERROR_ACTIVITY_NOT_FOUND);
            return false;
        }else{
            switch ($activity->type){
                case \common\models\MerchantActivity::TYPE_DISCOUNT:
                    return new DiscountActivity($this->merchant,$activity);
                case \common\models\MerchantActivity::TYPE_FULL_CUT:
                    return new FullCutActivity($this->merchant,$activity);
                case \common\models\MerchantActivity::TYPE_SPECIAL_OFFER:
                    return new SpecialActivity($this->merchant,$activity);
                default:
                    $this->setError(ErrorManager::ERROR_ACTIVITY_WRONG);
                    return false;
            }
        }
    }

    /**
     * 下单
     * @param array $rooms
     * @param $totalAmount
     * @param $activityId
     * @return bool
     */
    protected function doPlay(array $rooms,$totalAmount,$activityId){
        if($totalAmount>=0){
            $this->setTemporaryAmount($totalAmount);
        }
        if($activityId>0){
            $activity=$this->findActivity($activityId);
            if(!$activity){
                return false;
            }
        }else{
            $activity=null;
        }
        $bill=$this->generateBill($rooms,$activity);
        if(!$bill){
            return false;
        }
        if($activity){
            $activity->active();
        }
        if(!$this->add()){
            $this->setError(ErrorManager::ERROR_ORDER_INSERT_FAIL);
            return false;
        }else if(!$bill->reverse()){

        }
    }

    protected function generateBill(array $rooms,$activity){
        $orderBill=new OrderBill($this);
        foreach ($rooms as $room){
            $room=Room::byId($this->merchant,$room['roomId']);
            if(empty($room)){
                $this->setError(ErrorManager::ERROR_ROOM_UN_FIND);
                return false;
            }else{
                if($room['type']==OrderRoom::TYPE_DAY){
                    $orderBill->generateDay($room,$room['startTime'],$room['quantity'],$activity);
                }else{
                    $orderBill->generateHour($room,$room['startTime'],$room['quantity'],$activity);
                }
            }
        }
        return $orderBill;
    }
    public function reverse(){

    }

    public function occupancy(){

    }

    public function recordOccupancy(array $guests){

    }
}