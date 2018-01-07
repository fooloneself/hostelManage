<?php
namespace service\order\bill;
use common\models\OrderRoom;
use service\order\Room;
class HourRoomBillGenerator extends RoomBillGenerator {

    /**
     * 装填记录
     * @param RoomBill $roomBill
     * @return RoomBill
     */
    protected function fillBill(RoomBill $roomBill)
    {
        $totalAmount=$roomBill->getRoom()->getHourPrice();
        $roomBill->generateBill($roomBill->getStartTime(),$totalAmount);
        return $roomBill;
    }

    /**
     * 新实例
     * @param Room $room
     * @param $start
     * @param $quantity
     * @return OrderRoom
     */
    protected function newOrderRoom(Room $room,$start,$quantity){
        $orderRoom=new OrderRoom();
        $orderRoom->start_time=$start;
        $orderRoom->end_time=$this->getEndTime($start,$quantity);
        $orderRoom->quantity=$quantity;
        $orderRoom->type=OrderRoom::TYPE_CLOCK;
        return $orderRoom;
    }
    /**
     * 获取房间订单时间段
     * @param $startTime
     * @param $quantity
     * @return $this
     */
    protected function getEndTime($startTime,$quantity){
        return $startTime+$quantity*3600;
    }
}