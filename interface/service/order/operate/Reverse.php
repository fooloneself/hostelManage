<?php
namespace service\order\operate;
use common\components\ErrorManager;
class Reverse extends Operate {

    public function toDo($totalAmount = -1)
    {
        $this->order->setTemporaryAmount($totalAmount);
        $bill=$this->generateBill($this->activity);
        $this->order->setIsReverse();
        if(!$this->add()){
            $this->setError(ErrorManager::ERROR_ORDER_INSERT_FAIL);
            return false;
        }else if(!$bill->reverse($this)){
            $this->setError($bill->getError());
            return false;
        }else if(!$this->pay()){
            return false;
        }else{
            return true;
        }
    }


}