<?php
namespace service\order\operate;
use service\order\Order;
use service\order\Room;
use common\models\OrderRoom;
class CheckOut extends Operate{
    protected $room;
    public function room(Room $room){
        $this->room=$room;
        return $this;
    }

    /**
     * 修改订单中房间的状态
     * @param Order $order
     * @param Room $room
     * @return bool
     */
    protected function checkOutRoom(Order $order,Room $room){
        $orderRoom=OrderRoom::findOne(['order_id'=>$order->getId(),'room_id'=>$room->getId()]);
        if(empty($orderRoom)){
            $this->setError(ErrorManager::ERROR_ORDER_ROOM_UN_PLACE);
            return false;
        }
        $orderRoom->status=OrderRoom::STATUS_CHECK_OUT;
        $orderRoom->end_time=$_SERVER['REQUEST_TIME'];
        if($orderRoom->update(false)){
            return true;
        }else{
            $this->setError(ErrorManager::ERROR_ORDER_ROOM_CHANGE_FAIL);
            return false;
        }
    }

    protected function beforeOrder(Order $order)
    {
        if(!$this->room->setDirty()){
            $this->setError($this->room->getError());
            return false;
        }else if(!$this->checkOutRoom($order,$this->room)){
            return false;
        }
        $paying=$this->payBill->getPayingAmount();
        $paid=$order->getPaidAmount();
        $order->setPaidAmount($paying+$paid);
    }

    protected function afterOrder(Order $order)
    {
        if(!$this->savePay($order)){
            return false;
        }
        return true;
    }

    public function getOrderBill()
    {
        // TODO: Implement getOrderBill() method.
    }
}