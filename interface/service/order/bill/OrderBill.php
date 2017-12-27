<?php
namespace service\order\bill;
use service\order\activity\Activity;
use service\order\Order;
use service\order\Room;
use common\models\OrderRoom;

class OrderBill extends \common\components\Server{
    private $roomBill=[];
    protected $activity;
    protected $totalAmount=0;

    public function __construct(Activity $activity=null)
    {
        $this->activity=$activity;
    }

    public function getRoomBill(Room $room){
        return isset($this->roomBill[$room->getId()]) ? $this->roomBill[$room->getId()] : null;
    }

    protected function addRoomBill(RoomBill $roomBill){
        $this->totalAmount+=$roomBill->getTotalAmount();
        $this->roomBill[$roomBill->getRoom()->getId()]=$roomBill;
    }

    public function getTotalAmount(){
        return $this->totalAmount;
    }

    public function generateDay(Room $room,$start,$quantity){
        $bill=DayRoomBillGenerator::instance($this->activity)->generate($room,$start,$quantity);
        if(!$room->canPlaceOrder($bill->getStartTime(),$bill->getEndTime(),OrderRoom::TYPE_CLOCK)){
            $this->setError($room->getError());
            return false;
        }else{
            $this->addRoomBill($bill);
            return true;
        }
    }

    public function generateHour(Room $room,$start,$quantity){
        $bill=HourRoomBillGenerator::instance($this->activity)->generate($room,$start,$quantity);
        if(!$room->canPlaceOrder($bill->getStartTime(),$bill->getEndTime(),OrderRoom::TYPE_CLOCK)){
            $this->setError($room->getError());
            return false;
        }else{
            $this->addRoomBill($bill);
            return true;
        }
    }

    public function reverse(Order $order){
        foreach ($this->roomBill as $roomBill){
            if(!$roomBill->reverse($order)){
                $this->setError($roomBill->getError());
                return false;
            }
        }
        return true;
    }

    public function occupancy(Order $order){
        foreach ($this->roomBill as $roomBill){
            if(!$roomBill->occupancy($order)){
                $this->setError($roomBill->getError());
                return false;
            }
        }
        return true;
    }
}