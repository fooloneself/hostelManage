<?php
namespace service\order;
use common\components\Server;
use service\guest\Guest;
use service\merchant\Merchant;


class Order extends Server{

    private $_order;
    protected $merchant;
    protected $rooms=[];
    protected $guest;
    protected $paying=[];
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
            }else{
                $orderRoom=OrderRoom::newOne($r);
            }
            $bill=$orderRoom->during($room['start'],$room['end'])->generateBill($totalAmount);
            $orderRooms[]=$orderRoom;
            $total+=$bill->getTotalAmount();
        }
        $pay=PayBill::byOrder($this);
        $payingAmount=$pay->pay($this->paying)->getPayingAmount();
        if(!$this->addOrder($total,$payingAmount,1)){
            return false;
        }
        foreach ($orderRooms as $orderRoom){
            if(!$orderRoom->reverse($this)){
                return false;
            }
        }
        if(!$pay->insert()){
            return false;
        }
        return true;
    }

    public function occupancy($rooms,$totalAmount=-1){
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
            }else{
                $orderRoom=OrderRoom::newOne($r);
            }
            $bill=$orderRoom->during($room['start'],$room['end'])->generateBill($totalAmount);
            $orderRooms[]=$orderRoom;
            $total+=$bill->getTotalAmount();
        }
        $pay=PayBill::byOrder($this);
        $payingAmount=$pay->pay($this->paying)->getPayingAmount();
        if(!$this->addOrder($total,$payingAmount,0)){
            return false;
        }
        foreach ($orderRooms as $orderRoom){
            if(!$orderRoom->occupancy($this)){
                return false;
            }
        }
        if(!$pay->insert()){
            return false;
        }
        return true;
    }

    /**
     * 新增订单
     * @param $totalAmount
     * @param $paidAmount
     * @param int $isReverse
     * @return bool
     */
    protected function addOrder($totalAmount,$paidAmount,$isReverse=0){
        $this->_order->amount_payable=$totalAmount;
        $this->_order->is_reverse=$isReverse?\common\models\Order::REVERSE_YES:\common\models\Order::REVERSE_NO;
        $this->_order->amount_paid=$paidAmount;
        $this->_order->place_time=$_SERVER['REQUEST_TIME'];
        $this->_order->guest_id=$this->guest->getId();
        $this->_order->status=\common\models\Order::STATUS_NORMAL;
        $this->_order->is_settlement=\common\models\Order::SETTLE_NO;
        $this->_order->amount_deffer=$totalAmount-$paidAmount;
        return $this->_order->insert();
    }
}