<?php
namespace service\order;
use common\components\ErrorManager;
use common\components\Server;
use common\models\OrderRoom;
use service\guest\Guest;
use service\merchant\Merchant;
use service\order\activity\ActiveIncubator;
use service\order\activity\Activity;
use service\order\bill\OrderBill;

class Order extends Server{
    protected $order;
    protected $merchant;
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
     * 新增订单
     * @param Merchant $merchant
     * @return static
     */
    public static function newOne(Merchant $merchant){
        $order=new \common\models\Order();
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
     * @return bool
     */
    public function byGuest($mobile,$name){
        $this->guest=Guest::by($this->merchant,$mobile,$name);
        if($this->guest){
            $this->order->guest_id=$this->guest->getId();
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取订单人
     * @return \service\guest\Guest
     */
    public function getGuest(){
        return $this->guest;
    }


    /**
     * 修改
     * @return bool
     */
    public function update(){
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
    public function add(){
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
    public function isSettle(){
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
    public function setAmount($amount){
        if(!$this->isTemporary()){
            $this->order->amount=$amount;
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
    public function setTemporaryAmount($amount){
        if($amount>=0){
            $this->order->amount=$amount;
            $this->order->amount_payable=$amount;
            $this->order->is_temporary=1;
        }
    }
    /**
     * 新增应收费用
     * @param $amount
     */
    public function setPayableAmount($amount){
        if(!$this->isTemporary()){
            $this->order->amount_payable=$amount;
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
     * 设置订单为预定
     */
    public function setIsReverse(){
        $this->order->is_reverse=1;
    }

    /**
     * 结单
     */
    public function setIsSettle(){
        $this->order->is_settlement=1;
    }

    /**
     * 设置订单来源渠道
     * @param $channelId
     */
    public function setChannel($channelId){
        $this->order->channel=$channelId;
    }

    public function addPaidAmount($amount){
        $this->order->amount_paid+=$amount;
    }
}