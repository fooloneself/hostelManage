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
    const STATUS_CLOCK       =4;//钟点房
    const STATUS_ALL_DAY    =5;//整天房
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
            if($roomStatus==self::STATUS_ALL_DAY || $roomStatus==self::STATUS_CLOCK){
                $guestName=strval($room['occupancy_name']);
            }else{
                $guestName=strval($room['guest_name']);
            }
            $res[]=[
                'roomNumber'=>$room['number'],
                'typeName'=>$room['type_name'],
                'orderId'=>intval($room['order_id']),
                'roomStatus'=>$roomStatus,
                'guestName'=>$guestName,
                'roomId'=>intval($room['id'])
            ];
        }
        return $res;
    }

    protected function flushFromDb($guestId,$type){
        $query=\common\models\Room::find()->alias('r')
            ->select('r.id,r.number,r.`status`,rt.`name` as type_name,room.order_room_status,room.guest_name,
            room.order_id,oom.type as occupancy_type,mm.name as occupancy_name')
            ->leftJoin(RoomType::tableName().' rt','r.`type`=rt.`id`')
            ->leftJoin(OrderRoom::tableName().' oom','oom.order_id=r.order_id and oom.room_id=r.id')
            ->leftJoin(Order::tableName().' o','o.id=oom.order_id')
            ->leftJoin(MerchantMember::tableName().' mm','mm.id=o.guest_id')
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
            ->where(['o.mch_id'=>$this->mchId,'o.status'=>Order::STATUS_NORMAL,'oo.status'=>OrderRoom::STATUS_REVERSE])
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
        }else if($room['status']==\common\models\Room::STATUS_OCCUPANCY){
            if($room['occupancy_type']==OrderRoom::TYPE_CLOCK){
                return self::STATUS_CLOCK;
            }else{
                return self::STATUS_ALL_DAY;
            }
        }else{
            if($room['order_room_status']==OrderRoom::STATUS_REVERSE){
                return self::STATUS_RESERVE;
            }else{
                return self::STATUS_EMPTY;
            }
        }
    }
}