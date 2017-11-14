<?php
namespace service\order;
use common\models\Order;
use common\models\OrderOccupancyRoom;
use common\models\OrderReserveRoom;
use common\models\OrderRoom;
use common\models\RoomDayPrice;
use common\models\RoomType;
use common\models\RoomWeekPrice;

class Room{
    //房间
    protected $room;
    protected $roomType;

    public function __construct(\common\models\Room $room)
    {
        $this->room=$room;
        $this->roomType=RoomType::findOne(['id'=>$this->room->type,'mch_id'=>$this->room->mch_id]);
    }

    /**
     * 房间是否存在
     * @return bool
     */
    public function exists(){
        return !empty($this->room);
    }

    /**
     * 是否已经设置分类
     * @return bool
     */
    public function hasSetType(){
        return !empty($this->roomType);
    }
    /**
     * 房间是否可下订单
     * @return bool
     */
    public function canPlaceOrder(){
        return $this->room->status!=\common\models\Room::STATUS_CAN_ORDER;
    }

    /**
     * 获取房间ID
     * @return int
     */
    public function getRoomId(){
        return $this->room->id;
    }

    /**
     * 是否预定
     * @param $startTime
     * @param $endTime
     * @return bool
     */
    public function hasReserve($startTime,$endTime){
        $order=Order::find()->alias('o')
            ->select('o.id')
            ->leftJoin(OrderReserveRoom::tableName().' or','o.id=or.order_id')
            ->where(['o.mch_id'=>$this->room->mch_id,'or.room_id'=>$this->room->id])
            ->andWhere('o.status=0 or o.status=1')
            ->andWhere('(or.plan_in_time>:startTime and or.plan_in_time<:endTime) or(or.plan_out_time>:startTime and or.plan_out_time<:endTime)',[
                ':startTime'=>$startTime,':endTime'=>$endTime
            ])
            ->andWhere('or.plan_in_time>=:startTime and or.plan_out_time<=:endTime',[
                ':startTime'=>$startTime,':endTime'=>$endTime
            ])
            ->asArray()->one();
        return !empty($order);
    }

    /**
     * 是否入住
     * @param $startTime
     * @param $endTime
     * @return bool
     */
    public function hasOccupancy($startTime,$endTime){
        $order=Order::find()->alias('o')
            ->leftJoin(OrderOccupancyRoom::tableName().' oor','o.id=oor.order_id')
            ->where(['o.mch_id'=>$this->room->mch_id,'oor.room_id'=>$this->room->id])
            ->andWhere('o.status=0 or o.status=1')
            ->andWhere('(oor.actual_in_time>:startTime and oor.actual_in_time<:endTime) or (oor.actual_out_time>:startTime and oor.actual_out_time<:endTime)',[
                ':startTime'=>$startTime,':endTime'=>$endTime
            ])
            ->andWhere('oor.actual_in_time>=:startTime and oor.actual_out_time<=:endTime',[
                ':startTime'=>$startTime,':endTime'=>$endTime
            ])
            ->asArray()->one();
        return !empty($order);
    }

    /**
     * @param int $startTime
     * @param int $endTime
     * @param null|\common\models\MerchantSet  $mchSetting
     * @return bool
     */
    public function allowClock($startTime,$endTime,$mchSetting){
        if(empty($mchSetting) || $mchSetting->auto_close_switch!=1 || !$this->roomType->allow_hour_room){
            return false;
        }else if(($startTime>$mchSetting->hour_room_start_time && $startTime<$mchSetting->hour_room_end_time)){
            return false;
        }else if($endTime>$mchSetting->hour_room_start_time && $endTime<$mchSetting->hour_room_end_time){
            return false;
        }else if($startTime<=$mchSetting->hour_room_start_time && $endTime>=$mchSetting->hour_room_end_time){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 计算钟点房费用
     * @param $startTime
     * @param $endTime
     * @return float
     */
    public function calculateHourCost($startTime,$endTime){
        return floor($this->roomType->hour_room_price)*ceil(($endTime-$startTime)/3600);
    }

    /**
     * 计算按天算房费
     * @param $startTime
     * @param $endTime
     * @return float
     */
    public function calculateDayCost($startTime,$endTime){
        $days=($endTime-$startTime)/86400;
        $startWeek=date('w',$startTime);
        $cycle=floor($days/7);
        $surplus=$days%7;
        $weekNum=array_fill(1,7,$cycle);
        for($key=0;$key<$surplus;$key++){
            $weekNum[$startWeek+$key]++;
        }
        $dayPriceList=RoomDayPrice::getDayPriceList($this->room->mch_id,$this->room->type,$startTime,$endTime);
        $totalCost=0;
        foreach ($dayPriceList as $dayPrice){
            $totalCost+=floatval($dayPrice['price']);
            $weekNum[$dayPrice['week']]--;
        }
        $weekPrice=RoomWeekPrice::find()
            ->where(['type_id'=>$this->room->type,'mch_id'=>$this->room->mch_id])
            ->one();
        $defaultPriceNum=0;
        if(empty($weekPrice)){
            $defaultPriceNum=array_sum($weekNum);
        }else{
            $weekSub=['monday','tuesday','wensday','thursday','friday','saturday','sunday'];
            foreach ($weekSub as $sub=>$week){
                $price=floatval($weekPrice->getAttribute($week));
                if($price<0){
                    $defaultPriceNum+=$weekNum[$sub+1];
                }else{
                    $totalCost+=$weekNum[$sub+1]*$price;
                }
            }
        }
        $totalCost+=$defaultPriceNum*floatval($this->roomType->default_price);
        return $totalCost;
    }

    /**
     * 通过房间ID实例化类
     * @param $mchId
     * @param $roomId
     * @return static
     */
    public static function byId($mchId,$roomId){
        $room=\common\models\Room::findOne(['mch_id'=>$mchId,'id'=>$roomId]);
        return new static($room);
    }
}