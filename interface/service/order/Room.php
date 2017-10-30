<?php
namespace service\order;
use common\models\Order;
use common\models\OrderOccupancyRoom;
use common\models\OrderReserveRoom;
use common\models\OrderRoom;
use common\models\RoomType;

class Room{
    //房间
    protected $room;
    protected $roomType;

    public function __construct($mchId,$roomId)
    {
        $this->room=\common\models\Room::findOne(['mch_id'=>$mchId,'id'=>$roomId]);
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
     * 房间是否可下订单
     * @return bool
     */
    public function canPlaceOrder(){
        return $this->room->status!=\common\models\Room::STATUS_CAN_ORDER;
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

    public function cost($startTime,$endTime,$type){
        if()
    }
}