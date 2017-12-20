<?php
namespace service\order\place;
use common\models\Order;

class ReverseOrder extends \service\order\place\Order {

    public function doPlay()
    {
        $this->order->is_reverse=Order::REVERSE_YES;
    }
}