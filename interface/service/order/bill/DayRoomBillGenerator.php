<?php
namespace service\order\bill;
use common\models\OrderRoom;
use service\order\Room;

class DayRoomBillGenerator extends RoomBillGenerator {

    /**
     * 生成清单
     * @param Room $room
     * @param $start
     * @param $quantity
     */
    public function generate(Room $room,$start,$quantity){
        $orderRoom=$this->newOrderRoom($room,$start,$quantity);
        if($this->activity){
            $roomBill=new ActivityRoomBill($room,$orderRoom,$this->activity);
        }else{
            $roomBill=new RoomBill($room,$orderRoom);
        }
        return $this->fillBill($roomBill);
    }

    /**
     * 装填记录
     * @param RoomBill $roomBill
     * @return RoomBill
     */
    protected function fillBill(RoomBill $roomBill)
    {
        $timestamp=$roomBill->getStartTime();
        $dayPrices=$roomBill->getRoom()->getPricesOfDay($timestamp,$roomBill->getEndTime());
        $weekPrices=$roomBill->getRoom()->getPricesOfWeek();
        for($i=0;$i<$roomBill->getQuantity();$i++){
            $date=date('Y/m/d',$timestamp);
            if(isset($dayPrices[$date])){
                $cost=$dayPrices[$date];
            }else if(!empty($weekPrices)){
                $week=intval(date('w',$timestamp));
                if($weekPrices[$week]>=0){
                    $cost=$weekPrices[$week];
                }else{
                    $cost=$roomBill->getRoom()->getDefaultPrice();
                }
            }else{
                $cost=$roomBill->getRoom()->getDefaultPrice();
            }
            $roomBill->generateBill($timestamp,$cost);
        }
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
        $orderRoom->end_time=$this->getEndTime($room,$start,$quantity);
        $orderRoom->quantity=$quantity;
        $orderRoom->type=OrderRoom::TYPE_DAY;
        return $orderRoom;
    }
    /**
     * 获取房间订单时间段
     * @param Room $room
     * @param $startTime
     * @param $quantity
     * @return $this
     */
    protected function getEndTime(Room $room,$startTime,$quantity){
        return strtotime(date('Y-m-d',$startTime+$quantity*86400).' '.$room->getMerchant()->getSetting()->check_out_time);
    }
}