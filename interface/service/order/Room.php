<?php
namespace service\order;
use common\components\Server;
use common\models\MerchantSet;
use common\models\OccupancyRecord;
use common\models\Order;
use common\models\OrderRoom;
use common\models\RoomDayPrice;
use common\models\RoomType;
use common\models\RoomWeekPrice;
use service\merchant\Merchant;

class Room extends Server{
    protected $merchant;
    protected $room;
    protected $roomType;
    public function __construct(Merchant $merchant,\common\models\Room $room,RoomType $roomType=null)
    {
        $this->merchant=$merchant;
        $this->room=$room;
        $this->roomType=$roomType;
    }

    /**
     * 实例化
     * @param Merchant $merchant
     * @param $id
     * @return null|static
     */
    public static function byId(Merchant $merchant,$id){
        $room=\common\models\Room::findOne(['id'=>$id,'mch_id'=>$merchant->getId()]);
        if(empty($room)){
            return null;
        }
        $roomType=RoomType::findOne(['id'=>$room->type]);
        return new static($merchant,$room,$roomType);
    }

    /**
     * 获取房间的默认价格
     * @return float
     */
    public function getDefaultPrice(){
        return floatval($this->roomType->default_price);
    }

    /**
     * 获取房间钟点单价
     * @return float
     */
    public function getHourPrice(){
        return floatval($this->roomType->hour_room_price);
    }
    /**
     * 获取房间id
     * @return int
     */
    public function getId(){
        return intval($this->room->id);
    }

    public function getNumber(){
        return intval($this->room->number);
    }
    /**
     * 获取商户
     * @return Merchant
     */
    public function getMerchant(){
        return $this->merchant;
    }

    /**
     * 获取单日价格
     * @param $start
     * @param $end
     * @return array
     */
    public function getPricesOfDay($start,$end){
        $dayPriceList=RoomDayPrice::getDayPriceList($this->room->mch_id,$this->room->type,$start,$end);
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
    public function getPricesOfWeek(){
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
     * 能否下单
     * @param $start
     * @param $end
     * @param int $type
     * @return bool
     */
    public function canPlaceOrder($start,$end,$type=OrderRoom::TYPE_DAY){
        $merchantSet=$this->merchant->getSetting();
        if($this->hasPlace($start,$end)){
            return false;
        }else if($type==OrderRoom::TYPE_CLOCK ){
            $hours=ceil(($end-$start)/3600);
            $start=date('H:s:i',$start);
            $end=date('H:s:i',$end);
            if($merchantSet->hour_room_switch==MerchantSet::HOUR_ROOM_NO){
                return false;
            }else if($start>$merchantSet->hour_room_start_time && $start<$merchantSet->hour_room_end_time){
                return false;
            }else if($end>$merchantSet->hour_room_start_time && $end<$merchantSet->hour_room_end_time){
                return false;
            }else if($start<=$merchantSet->hour_room_start_time && $end>=$merchantSet->hour_room_end_time){
                return false;
            }else if($merchantSet->clock_max_hour && $hours>$merchantSet->clock_max_hour){
                return false;
            }else{
                return true;
            }
        }
    }

    /**
     * 判断是否已定
     * @param $start
     * @param $end
     * @return bool
     */
    protected function hasPlace($start,$end){
        $order=OrderRoom::find()->alias('oo')
            ->leftJoin(Order::tableName().' o','oo.order_id=o.id')
            ->where([
                'o.status'=>Order::STATUS_NORMAL,
                'oo.room_id'=>$this->room->id
            ])
            ->andWhere('(:start>oo.start_time and :start<oo.end_time) or (:end>oo.start_time and :end <oo.end_time) 
            or (:start<=oo.start_time and :end>=oo.end_time)',[
                ':start'=>$start,
                ':end'=>$end
            ])
            ->asArray()->one();
        return !empty($order);
    }

    /**
     * 设为空房
     * @return false|int
     */
    public function setEmpty(){
        $this->room->status=\common\models\Room::STATUS_CAN_ORDER;
        return $this->room->update(false);
    }

    /**
     * 锁房
     * @return false|int
     */
    public function lock(){
        $this->room->status=\common\models\Room::STATUS_UN_OPEN;
        return $this->room->update(false);
    }

    /**
     * 设为脏房
     * @return false|int
     */
    public function setDirty(){
        $this->room->status=\common\models\Room::STATUS_DIRTY;
        return $this->room->update(false);
    }

    /**
     * 入住
     * @param \service\order\Order $order
     * @param array $guests
     * @return false|int
     */
    public function occupancy(\service\order\Order $order,array $guests){
        $this->room->status=\common\models\Room::STATUS_OCCUPANCY;
        $this->room->order_id=$order->getId();
        if(!$this->room->update(false)){
            return false;
        }
        $model=new OccupancyRecord();
        foreach ($guests as $guest){
            $model->setIsNewRecord(true);
            $model->setAttributes(null);
            $attributes=[
                'order_id'=>$order->getId(),
                'room_id'=>$this->getId(),
                'premises_id'=>$this->merchant->getPremise()->id,
                'check_in_time'=>$_SERVER['REQUEST_TIME'],
                'room_number'=>$this->getNumber(),
                'mobile'=>$guest['mobile'],
                'person_name'=>$guests['name']
            ];
            $model->setAttributes($attributes);
            if(!$model->insert(true,array_keys($attributes))){
                return false;
            }
        }
        return true;
    }

    public function getOrderRoom(\service\order\Order $order){
    }
}