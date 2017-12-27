<?php
namespace service\order\operate;
use common\components\Server;
use service\order\Order;
use service\order\PayBill;
use service\order\Room;
use common\models\OccupancyRecord;
use service\order\bill\OrderBill;
use common\models\OrderRoom;
abstract class Operate extends Server{
    protected $payBill;
    /**
     * 订单支付清单
     * @param PayBill $payBill
     * @return $this
     */
    public function setPayBill(PayBill $payBill){
        $this->payBill=$payBill;
        return $this;
    }

    protected function getPayingAmount(){
        return empty($this->payBill)?0:$this->getPayingAmount();
    }

    /**
     * 生成订单消费清单
     * @param Order $order
     * @param array $rooms
     * @return bool|OrderBill
     */
    protected function generateBill(Order $order,array $rooms){
        $orderBill=new OrderBill($order->getActivity());
        foreach ($rooms as $room){
            $room=Room::byId($order->getMerchant(),$room['roomId']);
            if(empty($room)){
                $this->setError(ErrorManager::ERROR_ROOM_UN_FIND);
                return false;
            }else{
                if($room['type']==OrderRoom::TYPE_DAY){
                    $orderBill->generateDay($room,$room['startTime'],$room['quantity']);
                }else{
                    $orderBill->generateHour($room,$room['startTime'],$room['quantity']);
                }
            }
        }
        return $orderBill;
    }

    /**
     * 记录房间入住人信息
     * @param Order $order
     * @param Room $room
     * @param array $guests
     * @return bool
     */
    protected function recordOccupancy(Order $order,Room $room,array $guests){
        foreach ($guests as $guest){
            $model=new OccupancyRecord();
            $model->order_id=$order->getId();
            $model->room_id=$room->getId();
            $model->mch_id=$order->getMerchant()->getId();
            $model->premises_id=$order->getMerchant()->getPremise()->id;
            $model->check_in_time=$_SERVER['REQUEST_TIME'];
            $model->room_number=$room->getNumber();
            $model->mobile=$guest['mobile'];
            $model->person_name=$guest['name'];
            if(!$model->insert()){
                return false;
            }
        }
        return true;
    }

    /**
     * 保存支付信息
     * @param Order $order
     * @return bool
     */
    protected function savePay(Order $order){
        if($this->payBill && !$this->payBill->insert($order)){
            $this->setError($this->payBill->getError());
            return false;
        }else{
            return true;
        }
    }

    public function doOrder(Order $order){
        if(!$this->beforeOrder($order)){
            return false;
        }
        if(!$order->save()){
            $this->setError($order->getError());
            return false;
        }
        if(!$this->afterOrder($order)){
            return false;
        }
        return true;
    }

    /**
     * 保存订单前
     * @param Order $order
     * @return bool
     */
    abstract protected function beforeOrder(Order $order);

    /**
     * 保存订单后
     * @param Order $order
     * @return mixed
     */
    abstract protected function afterOrder(Order $order);
}