<?php
namespace service\order\activity;
use common\models\OrderCostDetail;
use service\order\Order;

class SpecialActivity extends Activity{

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
        return $costDetail->amount - $this->getDiscount();
    }
}