<?php
namespace service\order\activity;
use common\models\OrderCostDetail;

class SpecialActivity extends Activity{

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
        return $costDetail->amount - $this->getDiscount();
    }
}