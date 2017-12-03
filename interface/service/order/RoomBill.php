<?php
namespace service\order;
use common\components\Server;
use common\models\Room;
use common\models\RoomWeekPrice;

class RoomBill extends Server{
    protected $room;
    protected $bill;
    public function __construct(Room $room)
    {
        $this->room=$room;
    }

    /**
     * 生成费用记录-钟点房
     * @return float    总费用
     */
    public function generateHourCostBill(){
        $cost=$this->calculateHourCost();
        $this->addCostRecord($this->startTime,$cost);
        return $cost;
    }

    /**
     * 生成消费记录-天
     * @return float|int|mixed 总费用
     */
    public function generateDayCostBill(){
        $days=ceil(($this->endTime-$this->startTime)/86400);
        $timestamp=$this->startTime;
        if($this->price>0){
            for($i=0;$i<$days;$i++){
                $timestamp+=86400;
                $this->addCostRecord($timestamp,$this->price);
            }
            $totalCost=$days*$this->price;
        }else{
            $totalCost=0;
            $dayPrices=$this->getPricesOfDay();
            $weekPrices=$this->getPricesOfWeek();
            for($i=0;$i<$days;$i++){
                $timestamp+=86400;
                $date=intval(date('Ymd',$timestamp));
                if(isset($dayPrices[$date])){
                    $cost=$dayPrices[$date];
                }else if(!empty($weekPrices)){
                    $week=intval(date('w',$timestamp));
                    if($weekPrices[$week]>=0){
                        $cost=$weekPrices[$week];
                    }else{
                        $cost=floatval($this->roomType->getAttribute('default_price'));
                    }
                }else{
                    $cost=floatval($this->roomType->getAttribute('default_price'));
                }
                $this->addCostRecord($timestamp,$cost);
                $totalCost+=$cost;
            }
        }
        return $totalCost;
    }

    /**
     * 获取单日价格
     * @return array
     */
    protected function getPricesOfDay(){
        $dayPriceList=RoomDayPrice::getDayPriceList($this->room->mch_id,$this->room->type,$this->startTime,$this->endTime);
        $prices=[];
        foreach ($dayPriceList as $dayPrice){
            $prices[$dayPrice['date']]=floatval($dayPrice['price']);
        }
        return $prices;
    }

    /**
     * 获取周价格
     * @return array
     */
    protected function getPricesOfWeek(){
        $prices=RoomWeekPrice::find()
            ->where(['type_id'=>$this->room->type,'mch_id'=>$this->room->mch_id])
            ->asArray()->one();
        if(empty($prices)){
            return [];
        }else{
            return [
                floatval($prices['monday']),
                floatval($prices['tuesday']),
                floatval($prices['wensday']),
                floatval($prices['thursday']),
                floatval($prices['friday']),
                floatval($prices['saturday']),
                floatval($prices['sunday'])
            ];
        }
    }

    /**
     * 添加消费记录
     * @param $timestamp
     * @param $amount
     */
    protected function addCostRecord($timestamp,$amount){
        $date=date('Y-m-d',$timestamp);
        list($year,$month,$day)=explode('-',$date);
        $date=intval(str_replace('-','',$date));
        $this->bill[]=[
            'date'=>$date,
            'year'=>$year,
            'month'=>$month,
            'day'=>$day,
            'amount'=>$amount
        ];
    }
}