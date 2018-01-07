<?php
namespace service\order\operate;
use common\components\Server;
use service\order\Order;
use service\order\PayBill;
use service\order\Room;
use common\models\OccupancyRecord;
use service\order\bill\OrderBill;
use service\merchant\Merchant;
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

    /**
     * 获取将要支付的金额
     * @return int|mixed
     */
    protected function getPayingAmount(){
        return empty($this->payBill)?0:$this->getPayingAmount();
    }

    /**
     * 生成订单消费清单
     * @param Merchant $merchant
     * @param array $rooms
     * @return bool|OrderBill
     */
    protected function generateBill(Merchant $merchant,array $rooms){
        $orderBill=new OrderBill($merchant);
        if(!$orderBill->generate($rooms)){
            $this->setError($orderBill->getError());
            return false;
        }else{
            return $orderBill;
        }
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

    /**
     * 执行
     * @param Order $order
     * @return bool
     */
    public function doOrder(Order $order){
        if(!$this->beforeOrder($order)){
            return false;
        }
        $orderBill=$this->getOrderBill($order);
        if(!$orderBill){
            return false;
        }
        $totalAmount=$orderBill->getTotalAmount();
        $order->setAmount($totalAmount);
        $order->setPaidAmount($order->getPaidAmount()+$this->getPayingAmount());
        if(!$order->save($orderBill)){
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

    /*
     * 获取订单消费明细
     * @param Order $order
     * @return \service\order\bill\OrderBill|bool
     */
    abstract protected function getOrderBill(Order $order);
}