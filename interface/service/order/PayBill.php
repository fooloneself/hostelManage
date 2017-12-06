<?php
namespace service\order;
use common\components\Server;
use common\models\OrderPayDetail;

class PayBill extends Server{
    protected $order;
    protected $payingBill=[];
    protected $paidBill;
    protected $paying=0;
    protected $paid=0;
    public function __construct(Order $order)
    {
        $this->order=$order;
    }

    public static function byOrder(Order $order){
        return new static($order);
    }

    public function pay(array $bills){
        foreach ($bills as $bill){
            $this->paying+=$bill['amount'];
            $model=new OrderPayDetail();
            $model->amount=$bill['amount'];
            $model->expense_item=$bill['expenseItem'];
            $model->channel=$bill['channel'];
            $this->payingBill[]=$model;
        }
        return $this;
    }

    public function getPayingAmount(){
        return $this->paying;
    }

    public function getPaidBill(){
        $this->refreshPaidBill();
        return $this->paidBill;
    }

    protected function refreshPaidBill(){
        if($this->paidBill===null){
            $this->paidBill=OrderPayDetail::findAll(['order_id'=>$this->order->getId()]);
            $paid=0;
            foreach ($this->paidBill as $bill){
                $paid+=$bill->amount;
            }
            $this->paid=$paid;
        }
    }

    public function getPaidAmount(){
        $this->refreshPaidBill();
        return $this->paid;
    }
    public function insert(){
        foreach ($this->bill as $bill){
            $bill->order_id=$this->order->getId();
            if(!$bill->insert()){
                return false;
            }
        }
        return true;
    }
}