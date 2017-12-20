<?php
namespace service\order\activity;
use common\components\Server;
use common\models\MerchantActiveDate;
use common\models\MerchantActivity;
use common\models\MerchantActivityCondition;
use common\models\OrderCostDetail;
use service\order\place\Order;

abstract class Activity extends Server{
    protected $activity;
    private $condition;
    protected $order;
    private $dates=[];
    protected $activityBill=[];

    /**
     * 构造函数
     * Activity constructor.
     * @param Order $order
     * @param MerchantActivity $activity
     */
    public function __construct(Order $order,MerchantActivity $activity)
    {
        $this->order=$order;
        $this->activity=$activity;
        $this->dates=$this->getDate();
        $this->initCondition();
    }

    /**
     * 获取学校ID
     * @return int
     */
    protected function getId(){
        return intval($this->activity->id);
    }

    /**
     * 获取活动日期
     * @return array
     */
    private function getDate(){
        return MerchantActiveDate::find()
            ->select('active_date')
            ->where(['active_id'=>$this->getId()])
            ->column();
    }

    /**
     * 添加条件
     * @param $category
     * @param $item
     */
    private function addCondition($category,$item){
        if(!isset($this->condition[$category])){
            $this->condition[$category]=[];
        }
        $this->condition[]=$item;
    }

    /**
     * 初始化条件
     */
    private function initCondition(){
        $options=MerchantActivityCondition::find()
            ->select('type,condition_identity')
            ->where(['active_id'=>$this->getId()])
            ->asArray()->all();
        foreach ($options as $option){
            $this->addCondition($option['type'],$option['condition_identity']);
        }
    }

    /**
     * 获取条件
     * @param $category
     * @return null
     */
    protected function getCondition($category){
        return isset($this->condition[$category])? $this->condition[$category]:null;
    }

    /**
     * 是否在优惠时间内
     * @param $date
     * @return bool
     */
    protected function isInDate($date){
        return in_array($date,$this->dates);
    }

    /**
     * 是否在会员范围内
     * @return bool
     */
    protected function isInMemberRank(){
        return in_array($this->order->getGuest()->getMemberRank(),$this->getCondition(MerchantActivityCondition::TYPE_MEMBER_RANK));
    }

    /**
     * 是否在房间范围内
     * @param $roomId
     * @return bool
     */
    protected function isInRoom($roomId){
        return in_array($roomId,$this->getCondition(MerchantActivityCondition::TYPE_ROOM_ID));
    }

    /**
     * 获取优惠金额
     * @return float
     */
    protected function getDiscount(){
        return floatval($this->activity->discount);
    }

    /**
     * 订单是否适合此活动
     * @return mixed
     */
    abstract protected function isSuitToOrder();

    /**
     * 放入符合活动的消费
     * @param OrderCostDetail $costDetail
     * @return mixed
     */
    public function putSuitCostBill(OrderCostDetail $costDetail){
        if($this->isInDate($costDetail->date) && $this->isInRoom($costDetail->room_id)){
            $this->activityBill[]=$costDetail;
        }
    }

    /**
     * 激活
     */
    public function active(){
        if(!$this->isSuitToOrder()){
            return false;
        }
        if(!empty($this->activityBill)){
            foreach ($this->activityBill as $bill){
                $deffer=$this->getBillDeffer($bill);
                $this->order->addPayableAmount($deffer);
            }
            $this->order->addPayableAmount($this->getOrderDeffer());
        }else{
            return false;
        }
    }
    /**
     * 单挑消费记录差价
     * @param OrderCostDetail $costDetail
     * @return mixed
     */
    abstract protected function getBillDeffer(OrderCostDetail $costDetail);

    /**
     * 整单差价
     * @return mixed
     */
    abstract protected function getOrderDeffer();
}