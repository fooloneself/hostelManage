<?php
namespace service\order\activity;
use common\models\OrderCostDetail;

class DiscountActivity extends Activity{
    protected function isSuitToOrder()
    {
        return $this->isInMemberRank();
    }

    protected function getOrderDeffer()
    {
        return 0;
    }

    protected function getBillDeffer(OrderCostDetail $costDetail)
    {
        return (1-$this->getDiscount()/10)*$costDetail->amount;
    }
}