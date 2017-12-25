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
}