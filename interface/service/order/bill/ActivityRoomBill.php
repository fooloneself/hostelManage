<?php
namespace service\order\bill;
class ActivityRoomBill extends RoomBill{
    protected $activity;
    public function __construct(Room $room,OrderRoom $orderRoom,Activity $activity)
    {
        parent::__construct($room,$orderRoom);
        $this->activity=$activity;
    }

    public function generateBill($timestamp,$amount){
        $model=$this->newBillModel($timestamp,$amount);
        $this->activity->putSuitCostBill($model);
        $this->addBill($model);
    }

    public function loadBill(Order $order)
    {
        parent::loadBill($order);
        foreach ($this->bill as $bill){
            $this->activity->putSuitCostBill($bill);
        }
    }
}