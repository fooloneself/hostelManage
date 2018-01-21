<?php
namespace service\order\operate;
use common\components\ErrorManager;
use service\order\bill\OrderBill;
use service\order\Order;
use service\order\Room;

class GoOn extends Operate{
    protected $room;
    protected $quantity;
    public function __construct(Room $room)
    {
        $this->room=$room;
    }

    public function quantity($quantity){
        $this->quantity=$quantity;
        return $this;
    }

    protected function beforeOrder(Order $order, OrderBill $orderBill)
    {
        $roomBill=$orderBill->getRoomBill($this->room);
        if(!$roomBill->goOn($orderBill,$this->room,$this->quantity)){
            $this->setError($roomBill->getError());
            return false;
        }
        return true;
    }

    protected function afterOrder(Order $order, OrderBill $orderBill)
    {
        if(!$orderBill->occupancy($order)){
            $this->setError($orderBill->getError());
            return false;
        }
        return true;
    }

    protected function getOrderBill(Order $order)
    {
        return $this->loadBill($order);
    }
}