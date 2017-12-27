<?php
namespace service\order\activity;
use common\models\OrderCostDetail;
use service\order\Order;

class DiscountActivity extends Activity{
    protected function isSuitToOrder(Order $order)
    {
        return $this->isInMemberRank($order->getGuest());
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