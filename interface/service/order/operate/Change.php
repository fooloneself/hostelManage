<?php
namespace service\order\operate;
use service\order\bill\OrderBill;
use service\order\Order;
use service\order\Room;

class Change extends Operate{
    protected $fromRoom;
    protected $toRoom;
    public function __construct(Room $room)
    {
        $this->fromRoom=$room;
    }

    public function to(Room $room){
        $this->toRoom=$room;
        return $this;
    }
    protected function beforeOrder(Order $order, OrderBill $orderBill)
    {
        $fromRoomBill=$orderBill->getRoomBill($this->fromRoom);
        if(!$fromRoomBill->change($orderBill,$this->toRoom,$_SERVER['REQUEST_TIME'])){
            $this->setError($fromRoomBill->getError());
            return false;
        }
        return true;
    }

    protected function afterOrder(Order $order, OrderBill $orderBill)
    {
        if(!$this->fromRoom->setDirty()){
            $this->setError($this->fromRoom->getError());
            return false;
        }
        $fromRoomBill=$orderBill->getRoomBill($this->fromRoom);
        if(!$fromRoomBill->checkOut()){
            $this->setError($fromRoomBill->getError());
            return false;
        }
        $toRoomBill=$orderBill->getRoomBill($this->toRoom);
        if(!$toRoomBill->occupancy($order)){
            $this->setError($toRoomBill->getError());
            return false;
        }
        return true;
    }

    protected function getOrderBill(Order $order)
    {
        return $this->loadBill($order);
    }
}