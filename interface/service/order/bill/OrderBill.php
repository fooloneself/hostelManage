<?php
namespace service\order\bill;
use service\order\activity\Activity;
use service\order\place\Order;
use service\order\Room;
use common\models\OrderRoom;

class OrderBill extends \common\components\Server{
    private $roomBill=[];
    protected $order;
    public function __construct(Order $order)
    {
        $this->order=$order;
    }

    public function getRoomBill(Room $room){
        return isset($this->roomBill[$room->getId()]) ? $this->roomBill[$room->getId()] : null;
    }

    public function generateHour(Room $room,$start,$quantity,Activity $activity){
        $bill=HourRoomBill::newOne($room,$activity)->quantity($start,$quantity);
        $bill->generate($this);
        if(!$room->canPlaceOrder($bill->getStartTime(),$bill->getEndTime(),OrderRoom::TYPE_CLOCK)){
            return false;
        }
        $this->roomBill[$room->getId()]=$bill;
    }

    public function generateDay(Room $room,$start,$quantity,Activity $activity){
        $bill=DayRoomBill::newOne($room,$activity)->quantity($start,$quantity);
        $bill->generate($this);
        if(!$room->canPlaceOrder($bill->getStartTime(),$bill->getEndTime(),OrderRoom::TYPE_CLOCK)){
            return false;
        }
        $this->roomBill[$room->getId()]=$bill;
        return true;
    }

    public function reverse(){
        foreach ($this->roomBill as $roomBill){
            if(!$roomBill->reverse($this->order)){
                $this->setError($roomBill->getError());
                return false;
            }
        }
        return true;
    }

    public function occupancy(){
        foreach ($this->roomBill as $roomBill){
            if(!$roomBill->occupancy($this->order)){
                $this->setError($roomBill->getError());
                return false;
            }
        }
        return true;
    }
    public function addAmount($amount){
        $this->order->addAmount($amount);
    }

    public function addPayableAmount($amount){
        $this->order->addPayableAmount($amount);
    }
}