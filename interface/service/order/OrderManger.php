<?php
namespace service\order;
use common\components\ErrorManager;
use common\components\Server;
use common\models\Order;
use common\models\OrderPayDetail;
use service\guest\Guest;
use service\merchant\Merchant;

class OrderManger extends Server{
    protected $merchant;
    protected $guest;
    protected $order;
    protected $payableAmount=0;
    protected $paidAmount=0;
    protected $channel;
    protected $mark;
    protected $room;
    protected $pay=[];
    protected $lodgers;
    public function __construct(Merchant $merchant)
    {
        $this->merchant=$merchant;
    }

    /**
     * 生产订单号
     * @param Merchant $merchant
     * @return string
     */
    protected function makeOrderNo(Merchant $merchant){
        return 'A'.str_pad(base_convert($merchant->getId(),10,16),STR_PAD_LEFT,6).date('ymdHmi').rand(100,999);
    }

    /**
     * 绑定顾客
     * @param $mobile
     * @param $name
     * @return $this
     */
    public function guest($mobile,$name){
        $this->guest=Guest::by($this->merchant,$mobile,$name);
        if(empty($this->guest)){
            $this->setError(ErrorManager::ERROR_GUEST_SAVE_WRONG);
        }
        return $this;
    }

    /**
     * 获取下订单的人
     * @return \service\guest\Guest
     */
    public function getGuest(){
        return $this->guest;
    }

    /**
     * 获取订单ID
     * @return int
     */
    public function getId(){
        return intval($this->order->getAttribute('id'));
    }

    /**
     * 房间
     * @param $room
     * @return $this
     */
    public function room($room){
        $this->room=$room;
        return $this;
    }

    /**
     * 录入订单
     * @param $status
     * @param $channel
     * @param $mark
     * @return bool|Order
     */
    protected function insertOrderToDb($status){
        $order=new Order();
        $order->mch_id=$this->merchant->getId();
        $order->guest_id=$this->getGuest()->getId();
        $order->place_time=$_SERVER['REQUEST_TIME'];
        $order->amount_payable=$this->payableAmount;
        $order->status=$status;
        $order->amount_paid=$this->paidAmount;
        $order->amount_deffer=$this->payableAmount-$this->paidAmount;
        $order->order_no=$this->makeOrderNo($this->merchant);
        $order->channel=$this->channel;
        $order->mark=$this->mark;
        if($order->insert()){
            return $order;
        }else{
            return false;
        }
    }

    /**
     * 增加费用
     * @param $cost
     */
    protected function addCost($cost){
        $this->payableAmount+=$cost;
        return $this;
    }

    /**
     * 清除消费总额
     * @return $this
     */
    protected function cleanCost(){
        $this->payableAmount=0;
        return $this;
    }
    /**
     * 获取订单ID
     * @return int
     */
    public function Id(){
        return intval($this->order->getAttribute('id'));
    }

    /**
     * 支付
     * @param array $list
     * @return $this
     */
    public function pay(array $list){
        $this->pay=$list;
        $this->paidAmount=0;
        foreach ($list as $item){
            $this->paidAmount+=$item['amount'];
        }
        return $this;
    }

    /**
     * 备注
     * @param $mark
     * @param null $channel
     * @return $this
     */
    public function mark($mark,$channel=null){
        $this->channel=$channel;
        $this->mark=$mark;
        return $this;
    }

    /**
     * 设置住客信息
     * @param $lodgers
     * @return $this
     */
    public function lodger($lodgers){
        $this->lodgers=$lodgers;
        return $this;
    }
    /**
     * 初始化订单
     * @param $status
     * @return bool
     */
    protected function initOrder($status){
        $this->cleanCost();
        $rooms=[];
        $cost=0;
        foreach ($this->room as $r){
            $room=Room::byId($this->merchant,$r['roomId']);
            if(!$room->price($r['price'])->place($r['type'],$status,$r['start'],$r['end'])->isAllow()){
                $this->setError($room->getError());
                return false;
            }
            $cost+=$room->generateCostBill();
            $rooms[]=$room;
        }
        $this->addCost($cost);
        if(!($this->order=$this->insertOrderToDb($status))){
            return false;
        }
        foreach ($rooms as $room){
            if(!$room->insertToDb($this->order->id)){
                $this->setError($room->getError());
                return false;
            }else if(!$this->recordOccupancy($room)){
                return false;
            }
        }
        return true;
    }

    /**
     * 预定
     * @return bool
     */
    public function reverse(){
        if(empty($this->guest)){
            return false;
        }else if(!$this->initOrder(Order::STATUS_REVERSE)){
            return false;
        }else if(!$this->insertPayToDb()){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 入住
     * @return bool
     */
    public function occupancy(){
        if(empty($this->guest)){
            return false;
        }else if(!$this->initOrder(Order::STATUS_OCCUPANCY)){
            return false;
        }else if(!$this->insertPayToDb()){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 设置订单
     * @param $orderNo
     * @return $this
     */
    public function order($orderNo){
        $this->order=Order::findOne(['order_no'=>$orderNo,'mch_id'=>$this->merchant->getId()]);
        if(empty($this->order)){
            $this->setError(ErrorManager::ERROR_ORDER_NOT_EXISTS);
        }else if($this->order->status==Order::STATUS_CANCEL){
            $this->setError(ErrorManager::ERROR_ORDER_HAS_CANCEL);
        }else if($this->order->status==Order::STATUS_CHECK_OUT){
            $this->setError(ErrorManager::ERROR_ORDER_HAS_CHECK_OUT);
        }
        return $this;
    }

    /**
     * 入住预定的房间
     * @param $lodger
     * @return bool
     */
    public function occupancyByReserve(){
        if(empty($this->order)){
            return false;
        }
        //return $this->recordOccupancy();
    }

    /**
     * 录入入住记录
     * @param Room $room
     * @return bool
     */
    protected function recordOccupancy(Room $room){
        if(empty($this->lodgers))return true;
        if(!$room->occupancy($this->order->id,$this->lodgers)){
            $this->setError($room->getError());
            return false;
        }else{
            return true;
        }
    }

    /**
     * 录入支付详情
     * @return bool
     */
    protected function insertPayToDb(){
        if(empty($this->pay))return true;
        $model=new OrderPayDetail();
        $field=['order_id','amount','expense_item','channel'];
        foreach ($this->pay as $pay){
            $model->setIsNewRecord(true);
            $model->setOldAttributes(null);
            $model->setAttributes([
                'order_id'=>$this->order->id,
                'amount'=>$pay['amount'],
                'expense_item'=>$pay['expenseItem'],
                'channel'=>$pay['channel']
            ]);
            if(!$model->insert(false,$field)){
                return false;
            }
        }
        return true;
    }
}
