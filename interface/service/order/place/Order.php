<?php
namespace service\order\place;
use common\components\ErrorManager;
use common\components\Server;
use common\models\OrderRoom;
use service\guest\Guest;
use service\merchant\Merchant;
use service\order\bill\OrderBill;
use service\order\PayBill;
use service\order\Room;

abstract  class Order extends Server{
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
     * 新增实例化
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
     * 房间消费清单
     * @param OrderBill $orderBill
     * @return $this
     */
    public function roomBill(OrderBill $orderBill){
        $this->costBill=$orderBill;
        return $this;
    }

    /**
     * 计算金额
     */
    protected function calculateMoney(){
        if($this->costBill){
            $this->order->amount=$this->costBill->getTotalAmount();
            $this->order->amount_payable=$this->costBill->getPayableAmount();
        }
        if($this->payBill){
            $this->order->amount_paid+=$this->payBill->getPayingAmount();
        }
        $this->order->amount_deffer=$this->order->amount_payable-$this->order->amount_paid;
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
        $this->calculateMoney();
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
        $this->order->amount+=$amount;
        $this->order->amount_payable+=$amount;
    }

    /**
     * 新增应收费用
     * @param $amount
     */
    public function addPayableAmount($amount){
        $this->order->amount_payable-=$amount;
    }

    /**
     * 获取总消费金额
     * @return float
     */
    public function getAmount(){
        return floatval($this->order->amount);
    }
    /**
     * 下单
     * @return mixed
     */
    abstract public function doPlay();
}