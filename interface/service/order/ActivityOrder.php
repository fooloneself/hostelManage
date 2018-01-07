<?php
namespace service\order;
use common\models\Order;
use service\order\activity\Activity;
use service\merchant\Merchant;
use service\order\bill\OrderBill;
use service\order\bill\RoomBill;
use common\models\OrderCostDetail;

class ActivityOrder extends \service\order\Order {
    protected $activity;
    public function __construct(Merchant $merchant,Order $order,Activity $activity)
    {
        parent::__construct($merchant,$order);
        $this->activity=$activity;
    }

    /**
     * 新增订单
     * @param Merchant $merchant
     * @param Activity $activity
     * @return Order
     */
    public static function newOne(Merchant $merchant,Activity $activity){
        $order=new Order();
        $order->mch_id=$merchant->getId();
        return new \service\order\Order($merchant,$order,$activity);
    }

    /**
     * 计算优惠
     * @param OrderBill $orderBill
     * @return bool|int
     */
    public function calculateDiscount(OrderBill $orderBill){
        $activity=$this->activity;
        $orderBill->iterate(function (RoomBill $roomBill)use($activity){
            $roomBill->iterateBill(function (OrderCostDetail $costDetail)use($activity,$roomBill){
                $activity->putSuitCostBill($costDetail);
            });
        });
        if($activity->active($this)){
            $this->setError($activity->getError());
            return false;
        }else{
            return $activity->getTotalDiscount();
        }
    }

    /**
     * 保存优惠活动
     * @return bool
     */
    public function saveActivity(){
        if($this->activity->saveOrderActivity($this)){
            $this->setError($this->activity->getError());
            return false;
        }else{
            return true;
        }
    }

    /**
     * 获取订单活动
     * @return mixed
     */
    public function getActivity(){
        return $this->activity;
    }

    /**
     * 保存订单
     * @param OrderBill $orderBill
     * @return bool
     */
    public function save(OrderBill $orderBill){
        $discount=$this->calculateDiscount($orderBill);
        if(!$discount){
            return false;
        }
        $payableAmount=$this->getAmount()-$discount;
        $this->setPayableAmount($payableAmount);
        $this->setDefferAmount($payableAmount-$this->getPaidAmount());
        if(!parent::save()){
            return false;
        }
        if(!$this->saveActivity()){
            return false;
        }
        return true;
    }
}