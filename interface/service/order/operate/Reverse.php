<?php
namespace service\order\operate;
use service\order\Order;

class Reverse extends Operate{
    protected $costBill;
    protected $rooms;
    public function room($rooms){
        $this->rooms=$rooms;
        return $this;
    }

    protected function beforeOrder(Order $order)
    {
        $order->setIsReverse();
        return true;
    }

    protected function afterOrder(Order $order)
    {
        if(!$this->costBill->reverse($order)){
            $this->setError($this->costBill->getError());
            return false;
        }
        if(!$this->savePay($order)){
            return false;
        }
        return true;
    }

    protected function getOrderBill(Order $order)
    {
        $this->costBill=$this->generateBill($order->getMerchant(),$this->rooms);
        if(!$this->costBill){
            return false;
        }else{
            return $this->costBill;
        }
    }
}