<?php
namespace service\order;
use common\components\ErrorManager;
use common\components\Server;
use common\models\OrderRoom;
use service\guest\Guest;
use service\merchant\Merchant;
use service\order\activity\Activity;

class Order extends Server{
    protected $order;
    protected $merchant;
    protected $guest;
    protected $activity;
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

    public function setDefferAmount($amount){
        $this->order->amount_deffer=$amount;
        return $this;
    }
    /**
     * 计算优惠
     * @return bool|int
     */
    public function calculateDiscount(){
        $discount=0;
        if($this->activity){
            if($this->activity->active($this)){
                $this->setError($this->activity->getError());
                return false;
            }else{
                $discount=$this->activity->getTotalDiscount();
            }
        }
        return $discount;
    }

    /**
     * 保存优惠活动
     * @return bool
     */
    public function saveActivity(){
        if($this->activity){
            if($this->activity->saveOrderActivity($this)){
                $this->setError($this->activity->getError());
                return false;
            }
        }
        return true;
    }
    /**
     * 新增
     * @return bool
     */
    public function save(){
        if($this->getId()>0){
            $res=$this->order->update(false);
        }else{
            $res=$this->order->insert(false);
        }
        if($res){
            return true;
        }else{
            $this->setError(ErrorManager::ERROR_ORDER_PLACE_FAIL);
            return false;
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
     * 获取剩余需支付金额
     * @return float
     */
    public function getDefferAmount(){
        return floatval($this->order->amount_deffer);
    }

    /**
     * 获取已付金额
     * @return float
     */
    public function getPaidAmount(){
        return floatval($this->order->amount_paid);
    }
    /**
     * 设置订单为预定
     * @param bool $is
     */
    public function setIsReverse($is=true){
        $this->order->is_reverse=$is?1:0;
    }

    /**
     * 结单
     */
    public function setIsSettle(){
        if($this->isSettle()){
            $this->order->is_settlement=\common\models\Order::SETTLE_YES;
            if($this->order->amount_deffer==0){
                $this->order->status=\common\models\Order::STATUS_NORMAL;
            }else{
                $this->order->status=\common\models\Order::STATUS_ABNORMAL;
                $this->order->abnormal_type=\common\models\Order::ABNORMAL_DEFFER;
            }
            $this->order->is_settlement=1;
        }
    }

    /**
     * 设置订单来源渠道
     * @param $channelId
     */
    public function setChannel($channelId){
        $this->order->channel=$channelId;
    }

    public function setPaidAmount($amount){
        $this->order->amount_paid=$amount;
    }

    /**
     * 设置活动
     * @param Activity $activity
     * @return $this
     */
    public function activity(Activity $activity){
        $this->activity=$activity;
        return $this;
    }

    /**
     * 获取订单活动
     * @return mixed
     */
    public function getActivity(){
        return $this->activity;
    }
}