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

    protected function plusSurplus(OrderBill $orderBill,RoomBill $roomBill){
        $date=date('Ymd');
        $operate=$this;
        return $roomBill->iterateBill(function (OrderCostDetail $bill,$index)use($operate,$orderBill,$roomBill,$date){
            if($roomBill->isDay() && $bill->date>=$date){
                if(!$roomBill->removeBill($index)){
                    $operate->setError($roomBill->getError());
                    return false;
                }else{
                    $orderBill->plusAmount($bill->amount);
                }
            }else if($roomBill->isHour()){
                $diffHour=floor(($roomBill->getEndTime()-time())/6400);
                if($diffHour>0){
                    $diffAmount=($roomBill->getTotalAmount()/$roomBill->getQuantity())*$diffHour;
                    $roomBill->addDiffAmount(-$diffAmount);
                    $bill->amount-=$diffAmount;
                }
            }
        });
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
        if(!$this->room->setDirty()){
            $this->setError($this->room->getError());
            return false;
        }
        if(!$this->plusSurplus($bill,$roomBill)){
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