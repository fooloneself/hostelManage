<?php
namespace service\order\operate;
use service\order\bill\OrderBill;
use service\order\Order;
use service\order\Room;

class Occupancy extends Operate {
    protected $room;
    protected $guest;
    protected $roomId;
    public function room($roomId,$start,$quantity){
        $this->roomId=$roomId;
        $this->room=[['roomId'=>$roomId,'startTime'=>$start,'quantity'=>$quantity]];
        return $this;
    }

    public function guest(array $guests){
        $this->guest=$guests;
        return $this;
    }
    protected function beforeOrder(Order $order,OrderBill $bill)
    {
        $order->setIsReverse(false);
        return true;
    }

    protected function afterOrder(Order $order,OrderBill $bill)
    {
        if(!$bill->occupancy($order)){
            $this->setError($bill->getError());
            return false;
        }
        if(!$this->recordOccupancy($order,Room::byId($order->getMerchant(),$this->roomId),$this->guest)){
            return false;
        }
        return true;
    }

    protected function getOrderBill(Order $order)
    {
        return $this->generateBill($order->getMerchant(),$this->room);
    }
}