<?php
namespace service\order;
use common\components\ErrorManager;
use common\components\Server;
use common\models\Guest;
use common\models\Order;

class OrderManger extends Server{
    protected $mchId;
    protected $type;
    protected $start;
    protected $end;
    protected $orderNo;
    protected $order;
    protected $guest;
    protected $mark;

    public function __construct($mchId,$type,$orderNo=null)
    {
        $this->mchId=$mchId;
        $this->type=$type;
        $this->orderNo=$orderNo;
    }

    /**
     * 生产订单号
     * @return string
     */
    protected function makeOrderNo(){
        return 'A'.str_pad(base_convert($this->mchId,10,16),STR_PAD_LEFT,6).date('ymdHmi').rand(100,999);
    }

    /**
     * 获取订单号
     * @return null|string
     */
    protected function getOrderNo(){
        if($this->orderNo){
            $this->orderNo=$this->makeOrderNo();
        }
        return $this->orderNo;
    }
    /**
     * 判断是否预定
     * @return bool
     */
    public function hasReverse(){
        return !empty($this->reverseOrder);
    }

    /**
     * 开始时间
     * @param $start
     * @return $this
     */
    public function from($start){
        $this->start=$start;
        return $this;
    }

    /**
     * 结束时间
     * @param $end
     * @return $this
     */
    public function to($end){
        $this->end=$end;
        return $this;
    }

    /**
     * 房间
     * @param $roomId
     */
    public function room($roomId){

    }

    public function by($mobile,$name){
        $this->guest=Guest::findOne(['mobile'=>$mobile,'person_name'=>$name]);
        if(empty($this->guest)){
            $this->guest=new Guest();
            $this->guest->mch_id=$this->mchId;
            $this->guest->mobile=$mobile;
            $this->guest->person_name=$name;
            $this->guest->create_time=$_SERVER['REQUEST_TIME'];
            if(!$this->guest->insert()){
                $this->setError(ErrorManager::ERROR_GUEST_ERROR);
            }
        }
        return $this;
    }

    /**
     * 备注
     * @param $mark
     * @return $this
     */
    public function mark($mark){
        $this->mark=$mark;
        return $this;
    }

    /**
     * 创建订单失败
     * @return bool
     */
    protected function createOrder(){
        $this->order=new Order();
        $this->order->mch_id=$this->mchId;
        $this->order->order_no=$this->getOrderNo();
        $this->order->guest_id=$this->guest->getAttribute('id');
        $this->order->place_time=$_SERVER['REQUEST_TIME'];
        $this->order->type=$this->type;
        $this->mark=$this->mark;
        if($this->order->insert()){
            return true;
        }else{
            $this->setError(ErrorManager::ERROR_ORDER_CREATE_FAIL);
            return false;
        }
    }
}
