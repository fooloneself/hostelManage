<?php
namespace service\order\bill;
use service\order\activity\Activity;
use service\order\Order;
use service\order\Room;
use common\models\OrderRoom;

class OrderBill extends \common\components\Server{
    private $roomBill=[];
    protected $order;
    protected $activity;
    public function __construct(Order $order,Activity $activity=null)
    {
        $this->order=$order;
        $this->activity=$activity;
    }

    public function getRoomBill(Room $room){
        return isset($this->roomBill[$room->getId()]) ? $this->roomBill[$room->getId()] : null;
    }


    protected function addRoomBill(RoomBill $roomBill){
        $this->roomBill[$roomBill->getRoom()->getId()]=$roomBill;
    }

    public function generateDay(Room $room,$start,$quantity){
        $bill=DayRoomBillGenerator::instance($this->activity)->generate($room,$start,$quantity);
        if(!$room->canPlaceOrder($bill->getStartTime(),$bill->getEndTime(),OrderRoom::TYPE_CLOCK)){
            return false;
        }else{
            $this->addRoomBill($bill);
            return true;
        }
    }

    public function generateHour(Room $room,$start,$quantity){
        $bill=HourRoomBillGenerator::instance($this->activity)->generate($room,$start,$quantity);
        if(!$room->canPlaceOrder($bill->getStartTime(),$bill->getEndTime(),OrderRoom::TYPE_CLOCK)){
            return false;
        }else{
            $this->addRoomBill($bill);
            return true;
        }
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