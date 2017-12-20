<?php
namespace service\order\bill;

use common\models\OrderRoom;

class HourRoomBill extends RoomBill {
    const TYPE=OrderRoom::TYPE_CLOCK;
    public function quantity($startTime, $quantity)
    {
        $this->startTime=$startTime;
        $this->quantity=$quantity;
        $this->endTime=$startTime+$quantity*3600;
        return $this;
    }

    public function generate(OrderBill $orderBill)
    {
        $totalAmount=$this->room->getHourPrice();
        $orderBill->addAmount($totalAmount);
        $this->addBill($this->startTime,$totalAmount);
        return $this;
    }
}