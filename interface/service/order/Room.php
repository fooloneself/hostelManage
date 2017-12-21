<?php
namespace service\order;
use common\components\ErrorManager;
use common\components\Server;
use common\models\MerchantSet;
use common\models\OccupancyRecord;
use common\models\OrderRoom;
use common\models\RoomDayPrice;
use common\models\RoomType;
use common\models\RoomWeekPrice;
use service\merchant\Merchant;
use service\order\place\Order;

class Room extends Server{
    protected $merchant;
    protected $room;
    protected $roomType;

    protected $bill;
    protected $type;
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

    public function getTypeName(){
        return $this->roomType->name;
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
        $startDate=intval(date('Ymd',$start));
        $endDate=intval(date('Ymd',$end-86400));
        $dayPriceList=RoomDayPrice::getDayPriceList($this->room->mch_id,$this->room->type,$startDate,$endDate);
        $prices=[];
        foreach ($dayPriceList as $dayPrice){
            $prices=array_merge($prices,self::generateDayPrice($dayPrice['start_date'],$dayPrice['end_date'],$startDate,$endDate,$dayPrice['price']));
        }
        return $prices;
    }

    protected static function generateDayPrice($startDate,$endDate,$limitStart,$limitEnd,$price){
        $res=[];
        $start=strtotime($startDate);
        $end=strtotime($endDate);
        for($i=$start;$i<=$end;$i+=86400){
            $date=intval(date('Ymd',$i));
            if($date>=$limitStart && $date<=$limitEnd){
                $res[date('Y/m/d',$i)]=$price;
            }
        }
        return $res;
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
            $this->setError(ErrorManager::ERROR_ROOM_HAS_PLACE);
            return false;
        }else if($type==OrderRoom::TYPE_CLOCK ){
            $hours=ceil(($end-$start)/3600);
            $start=date('H:s:i',$start);
            $end=date('H:s:i',$end);
            if($merchantSet->hour_room_switch==MerchantSet::HOUR_ROOM_NO){
                $this->setError(ErrorManager::ERROR_ROOM_DENY_CLOCK);
                return false;
            }else if($start>$merchantSet->hour_room_start_time && $start<$merchantSet->hour_room_end_time){
                $this->setError(ErrorManager::ERROR_ROOM_CLOCK_OVER_LIMIT);
                return false;
            }else if($end>$merchantSet->hour_room_start_time && $end<$merchantSet->hour_room_end_time){
                $this->setError(ErrorManager::ERROR_ROOM_CLOCK_OVER_LIMIT);
                return false;
            }else if($start<=$merchantSet->hour_room_start_time && $end>=$merchantSet->hour_room_end_time){
                $this->setError(ErrorManager::ERROR_ROOM_CLOCK_OVER_LIMIT);
                return false;
            }else if($merchantSet->clock_max_hour && $hours>$merchantSet->clock_max_hour){
                $this->setError(ErrorManager::ERROR_OVER_CLOCK_MAX);
                return false;
            }else{
                return true;
            }
        }else{
            return true;
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
            ->leftJoin(\common\models\Order::tableName().' o','oo.order_id=o.id')
            ->where([
                'o.status'=>\common\models\Order::STATUS_NORMAL,
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
        $this->room->order_id=0;
        return $this->room->update(false);
    }

    /**
     * 锁房
     * @return false|int
     */
    public function lock(){
        $this->room->status=\common\models\Room::STATUS_UN_OPEN;
        $this->room->order_id=0;
        return $this->room->update(false);
    }

    /**
     * 设为脏房
     * @return false|int
     */
    public function setDirty(){
        $this->room->status=\common\models\Room::STATUS_DIRTY;
        $this->room->order_id=0;
        if($this->room->update(true)){
            return true;
        }else{
            echo json_encode($this->room->getAttributes());
            $this->setError(ErrorManager::ERROR_ROOM_STATUS_CHANGE_FAIL,'退房失败');
            return false;
        }
    }

    /**
     * 生成消费清单-钟点
     * @return \service\order\RoomBill
     */
    public function newBill(){
        return $this->bill=RoomBill::byRoom($this);
    }

    /**
     * 入住
     * @param \service\order\Order $order
     * @param $quantity
     * @param array $guests
     * @param $isNew
     * @return bool
     */
    public function occupancy(\service\order\Order $order,$quantity,array $guests,$isNew){
        $this->room->status=\common\models\Room::STATUS_OCCUPANCY;
        $this->room->order_id=$order->getId();
        if(!$this->room->update(false)){
            $this->setError(ErrorManager::ERROR_ROOM_STATUS_CHANGE_FAIL);
            return false;
        }else if(!$this->addOccupancyRecord($order,$guests)){
            $this->setError(ErrorManager::ERROR_OCCUPANCY_RECORD_ADD_FAIL);
            return false;
        }else if(!$order->occupancyRoom($this,$quantity,$isNew)){
            $this->setError($order->getError());
            return false;
        }else{
            return true;
        }
    }

    /**
     * 记录入住信息
     * @param \service\order\Order $order
     * @param array $guests
     * @return bool
     */
    protected function addOccupancyRecord(\service\order\Order $order,array $guests){
        $model=new OccupancyRecord();
        foreach ($guests as $guest){
            if(empty($guest['mobile']))continue;
            $model->setIsNewRecord(true);
            $model->setAttributes(null);
            $attributes=[
                'order_id'=>$order->getId(),
                'room_id'=>$this->getId(),
                'premises_id'=>$this->merchant->getPremise()->id,
                'check_in_time'=>$_SERVER['REQUEST_TIME'],
                'room_number'=>$this->getNumber(),
                'mobile'=>$guest['mobile'],
                'person_name'=>$guest['name']
            ];
            $model->setAttributes($attributes);
            if(!$model->insert(true,array_keys($attributes))){
                return false;
            }
        }
        return true;
    }

    /**
     * 获取消费清单
     * @return RoomBill
     */
    public function getBill(){
        return $this->bill;
    }

    /**
     * 获取房间入住人信息
     * @param Order $order
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getOccupancyGuest(\service\order\Order $order){
        return OccupancyRecord::find()
            ->select('mobile,person_name as name')
            ->where(['room_id'=>$this->getId(),'order_id'=>$order->getId()])
            ->asArray()->all();
    }
}