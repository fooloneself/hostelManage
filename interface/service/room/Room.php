<?php
namespace service\room;
use common\components\Server;
use common\models\MerchantMember;
use common\models\Order;
use common\models\OrderOccupancyRoom;
use common\models\OrderReserveRoom;
use common\models\OrderRoom;
use common\models\RoomDayPrice;
use common\models\RoomType;

class Room extends Server{
    //房间状态
    const STATUS_EMPTY      =0;//空房
    const STATUS_DIRTY      =1;//脏房
    const STATUS_LOCK       =2;//锁房
    const STATUS_RESERVE    =3;//预定
    const STATUS_OCCUPANCY  =4;//入住
    const STATUS_CLOCK       =5;//钟点房
    const STATUS_ALL_DAY    =6;//整天房
    protected $premisesId;
    protected $mchId;

    public function __construct($mchId,$premisesId)
    {
        $this->mchId=$mchId;
        $this->premisesId=$premisesId;
    }

    public function get($status=-1,$type=0,$guestId=0){
        $rooms=$this->flushFromDb($guestId,$type);
        $res=[];
        foreach ($rooms as $room){
            $roomStatus=$this->getRoomStatus($room);
            if($status>-1 && $roomStatus!=$status){
                continue;
            }
            $res[]=[
                'roomNumber'=>$room['number'],
                'typeName'=>$room['type_name'],
                'orderId'=>intval($room['order_id']),
                'roomStatus'=>$roomStatus,
                'guestName'=>strval($room['guest_name']),
                'roomId'=>intval($room['id'])
            ];
        }
        return $res;
    }

    protected function flushFromDb($guestId,$type){
        $query=\common\models\Room::find()->alias('r')
            ->select('r.id,r.number,r.`status`,rt.`name` as type_name,room.order_room_status,room.guest_name,room.order_id,rt.allow_hour_room')
            ->leftJoin(RoomType::tableName().' rt','r.`type`=rt.`id`')
            ->leftJoin('('.$this->getOrderRoom($guestId).') room','r.`id`=room.room_id')
            ->where(['r.mch_id'=>$this->mchId]);
        if($type>0){
            $query->andWhere(['r.type'=>$type]);
        }else{
            $query->andWhere('r.type>0');
        }
        if($guestId>0){
            $query->andWhere(['room.guest_id'=>$guestId]);
        }
        return $query->orderBy('r.number asc')->asArray()->all();
    }

    protected function getOrderRoom($guestId){
        $query= OrderRoom::find()->alias('oo')
            ->select('oo.`room_id`,oo.`status` AS order_room_status,mm.name AS guest_name,oo.order_id,o.guest_id')
            ->leftJoin(Order::tableName().' o','oo.`order_id`=o.`id`')
            ->leftJoin(MerchantMember::tableName().' mm','o.`guest_id`=mm.`id`')
            ->where(['o.mch_id'=>$this->mchId,'o.status'=>Order::STATUS_NORMAL])
            ->andWhere(':time between oo.start_time and oo.end_time',[':time'=>$_SERVER['REQUEST_TIME']]);
        if($guestId>0){
            $query->andWhere(['o.guest_id'=>$guestId]);
        }
        return $query->createCommand()->getRawSql();
    }

    protected function getRoomStatus(array $room){
        if($room['status']==\common\models\Room::STATUS_DIRTY){
            return self::STATUS_DIRTY;
        }else if($room['status']==\common\models\Room::STATUS_UN_OPEN){
            return self::STATUS_LOCK;
        }else{
            if($room['order_room_status']==OrderRoom::STATUS_REVERSE){
                return self::STATUS_RESERVE;
            }else if($room['order_room_status']==OrderRoom::STATUS_OCCUPANCY){
                return self::STATUS_OCCUPANCY;
            }else{
                return self::STATUS_EMPTY;
            }
        }
    }
}