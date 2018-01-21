<?php
namespace service\order\operate;
use service\order\bill\OrderBill;
use service\order\Order;

class Reverse extends Operate{
    protected $rooms;
    public function room($rooms){
        $this->rooms=$rooms;
        return $this;
    }

    protected function beforeOrder(Order $order,OrderBill $bill)
    {
        $order->setIsReverse();
        return true;
    }

    protected function afterOrder(Order $order,OrderBill $bill)
    {
        if(!$bill->reverse($order)){
            $this->setError($bill->getError());
            return false;
        }
        return true;
    }

    protected function getOrderBill(Order $order)
    {
        return $this->generateBill($order->getMerchant(),$this->rooms);
    }
}