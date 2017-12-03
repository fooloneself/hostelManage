<?php
namespace service\order;
use common\components\Server;
use service\merchant\Merchant;


class Order extends Server{

    private $_order;
    protected $merchant;
    protected $rooms=[];
    public function __construct(Merchant $merchant,\common\models\Order $order)
    {
        $this->_order=$order;
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
            return new static($merchant,$order);
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
            return new static($merchant,$order);
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

    public function reverse($rooms){

    }

    public function occupancy($rooms){

    }
}