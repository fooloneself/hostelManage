<?php
namespace service\order\bill;

class DayRoomBill extends RoomBill{
    public function quantity($startTime, $quantity)
    {
        $this->startTime=$startTime;
        $this->quantity=$quantity;
        $this->endTime=strtotime(date('Y-m-d',$startTime+$quantity*86400).' '.$this->room->getMerchant()->getSetting()->check_out_time);
        return $this;
    }

    public function generate(OrderBill $orderBill)
    {
        $timestamp=$this->startTime;
        $dayPrices=$this->room->getPricesOfDay($this->startTime,$this->endTime);
        $weekPrices=$this->room->getPricesOfWeek();
        for($i=0;$i<$this->quantity;$i++){
            $date=date('Y/m/d',$timestamp);
            if(isset($dayPrices[$date])){
                $cost=$dayPrices[$date];
            }else if(!empty($weekPrices)){
                $week=intval(date('w',$timestamp));
                if($weekPrices[$week]>=0){
                    $cost=$weekPrices[$week];
                }else{
                    $cost=$this->room->getDefaultPrice();
                }
            }else{
                $cost=$this->room->getDefaultPrice();
            }
            $this->addBill($timestamp,$cost);
            $orderBill->addAmount($cost);
        }
        return $this;
    }
}