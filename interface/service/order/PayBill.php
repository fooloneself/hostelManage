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

    /**
     * 实例化清单
     * @param Order $order
     * @return static
     */
    public static function byOrder(Order $order){
        return new static($order);
    }

    /**
     * 设置正要支付的清单
     * @param array $bills
     * @return $this
     */
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

    /**
     * 获取正要支付的总金额
     * @return int
     */
    public function getPayingAmount(){
        return $this->paying;
    }

    /**
     * 获取已记录的支付清单
     * @return mixed
     */
    public function getPaidBill(){
        $this->refreshPaidBill();
        return $this->paidBill;
    }

    /**
     * 获取已记录的支付
     */
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

    /**
     * 获取到已支付总金额
     * @return int
     */
    public function getPaidAmount(){
        $this->refreshPaidBill();
        return $this->paid;
    }

    /**
     * 记录支付
     * @return bool
     */
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