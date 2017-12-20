<?php
namespace service\order\bill;
use service\order\place\Order;
use service\order\Room;

class OrderBill{
    protected $activity;
    private $roomBill=[];
    protected $order;
    public function __construct(Order $order)
    {
        $this->order=$order;
    }

    public function getRoomBill(Room $room){
        return isset($this->roomBill[$room->getId()]) ? $this->roomBill[$room->getId()] : null;
    }

    public function reverse(Room $room,$start,$quantity){
        $bill=RoomBill::newOne($room)->quantity($start,$quantity);
        $bill->generate($this);
        $this->roomBill[$room->getId()]=$bill;
    }

    public function addAmount($amount){
        $this->order->addAmount($amount);
    }

    public function addPayableAmount($amount){
        $this->order->addPayableAmount($amount);
    }
}