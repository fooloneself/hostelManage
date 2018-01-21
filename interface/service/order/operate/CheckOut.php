<?php
namespace service\order\operate;
use common\components\ErrorManager;
use service\order\bill\OrderBill;
use service\order\bill\RoomBill;
use service\order\Order;
use service\order\Room;
use common\models\OrderCostDetail;
class CheckOut extends Operate{
    protected $room;
    public function __construct(Room $room){
        $this->room=$room;
    }
    /**
     * @param Order $order
     * @param OrderBill $bill
     * @return bool
     */
    protected function beforeOrder(Order $order,OrderBill $bill)
    {
        $roomBill=$bill->getRoomBill($this->room);
        if(empty($roomBill)){
            $this->setError(ErrorManager::ERROR_ORDER_ROOM_UN_PLACE);
            return false;
        }
        if($roomBill->plusSurplus($bill,$_SERVER['REQUEST_TIME'])===false){
            return false;
        }
        if(!$roomBill->checkOut()){
            return false;
        }
        return true;
    }

    /**
     * @param Order $order
     * @param OrderBill $bill
     * @return bool
     */
    protected function afterOrder(Order $order,OrderBill $bill)
    {
        if(!$this->room->setDirty()){
            $this->setError($this->room->getError());
            return false;
        }
        if(!$this->savePay($order)){
            return false;
        }
        return true;
    }

    /**
     * @param Order $order
     * @return OrderBill
     */
    protected function getOrderBill(Order $order)
    {
        return $this->loadBill($order);
    }
}