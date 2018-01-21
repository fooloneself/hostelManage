<?php
namespace service\order\bill;
use common\models\OrderRoom;
use service\order\Room;
abstract class RoomBillGenerator{
    private static $_instance;

    public static function instance(){
        if(self::$_instance===null){
            self::$_instance=new static();
        }
        return self::$_instance;
    }

    /**
     * 生成清单
     * @param Room $room
     * @param $start
     * @param $quantity
     * @return RoomBill
     */
    public function generate(Room $room,$start,$quantity){
        $orderRoom=$this->newOrderRoom($start,$quantity);
        $roomBill=new RoomBill($room,$orderRoom);
        return $this->fillBill($roomBill);
    }

    /**
     * 装填记录
     * @param RoomBill $roomBill
     * @return RoomBill
     */
    abstract protected function fillBill(RoomBill $roomBill);

    /**
     * 新实例
     * @param Room $room
     * @param $start
     * @param $quantity
     * @return OrderRoom
     */
    abstract protected function newOrderRoom(Room $room,$start,$quantity);
}