<?php
namespace service\order\operate;
use common\components\Server;
use service\order\Order;
use service\order\PayBill;
use service\order\Room;
use common\models\OccupancyRecord;
use service\order\activity\Activity;
use service\order\bill\OrderBill;
use common\models\OrderRoom;
abstract class Operate extends Server{
    protected $costBill;
    protected $payBill;
    protected $order;
    protected $rooms;
    protected $activity;
    public function __construct(Order $order)
    {
        $this->order=$order;
    }

    /**
     * 订单支付清单
     * @param PayBill $payBill
     * @return $this
     */
    public function setPayBill(PayBill $payBill){
        $this->payBill=$payBill;
        $this->order->addPaidAmount($payBill->getPayingAmount());
        return $this;
    }

    public function rooms(array $rooms){
        $this->rooms=$rooms;
        return $this;
    }

    public function activity(Activity $activity){
        $this->activity=$activity;
        return $this;
    }
    /**
     * 生成订单消费清单
     * @param Activity|null $activity
     * @return bool|OrderBill
     */
    protected function generateBill(Activity $activity=null){
        $orderBill=new OrderBill($activity);
        foreach ($this->rooms as $room){
            $room=Room::byId($this->order->getMerchant(),$room['roomId']);
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
        $total=$orderBill->getTotalAmount();
        $discount=0;
        if($activity){
            if(!$activity->active()){
                $this->setError($activity->getError());
                return false;
            }else{
                $discount=$activity->getTotalDiscount();
            }
        }
        $this->order->setAmount($total);
        $this->order->setPayableAmount($total-$discount);
        return $orderBill;
    }

    public function recordOccupancy(Room $room,array $guests){
        foreach ($guests as $guest){
            $model=new OccupancyRecord();
            $model->order_id=$this->order->getId();
            $model->room_id=$room->getId();
            $model->mch_id=$this->order->getMerchant()->getId();
            $model->premises_id=$this->order->getMerchant()->getPremise()->id;
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

    public function pay(){
        if($this->payBill && !$this->payBill->insert($this->order)){
            $this->setError($this->payBill->getError());
            return false;
        }else{
            return true;
        }
    }
    abstract public function toDo($totalAmount=-1);
}