<?php
namespace service\order\activity;
use common\models\OrderCostDetail;

class FullCutActivity extends Activity{
    /**
     * 获取满减的消费金额
     * @return float
     */
    protected function getConditionAmount(){
        return floatval($this->getCondition(MerchantActivityCondition::TYPE_CONSUMPTION_FULL)[0]);
    }

    /**
     * 是否达到消费金额
     * @return bool
     */
    protected function isFull(){
        return $this->order->getAmount()>=$this->getConditionAmount();
    }

    /**
     * 是否适合此订单
     * @return bool
     */
    protected function isSuitToOrder()
    {
        if($this->isInMemberRank() && $this->isFull()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取单挑消费优惠金额
     * @param OrderCostDetail $costDetail
     * @return int
     */
    protected function getBillDeffer(OrderCostDetail $costDetail)
    {
        return 0;
    }

    /**
     * 获取整单优惠金额
     * @return mixed
     */
    protected function getOrderDeffer()
    {
        return $this->getDiscount();
    }
}