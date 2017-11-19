<?php
namespace service\room;
use common\components\Server;
use common\models\Order;
use common\models\OrderOccupancyRoom;
use common\models\OrderReserveRoom;
use common\models\OrderRoom;
use common\models\RoomType;

class Room extends Server{
    //房间状态
    const STATUS_EMPTY      =0;//空房
    const STATUS_DIRTY      =1;//脏房
    const STATUS_LOCK       =2;//锁房
    const STATUS_RESERVE    =3;//预定
    const STATUS_OCCUPANCY  =4;//入住
    public static $allStatus=[
        self::STATUS_EMPTY=>'空房',
        self::STATUS_DIRTY=>'脏房',
        self::STATUS_LOCK=>'锁房',
        self::STATUS_RESERVE=>'预定',
        self::STATUS_OCCUPANCY=>'入住'
    ];
    public $time;
    public $type=0;
    public $status=-1;

    protected $premisesId;
    protected $mchId;

    public function __construct($mchId,$premisesId)
    {
        $this->mchId=$mchId;
        $this->premisesId=$premisesId;
    }

    /**
     * 获取数据
     * @return array
     */
    public function get(){
        $rooms=$this->getBaseInfoOfRooms();
        if(empty($rooms)){
            return [];
        }else{
            return $this->handleRooms($rooms);
        }
    }

    /**
     * 获取所有房间
     * @return array
     */
    protected function getBaseInfoOfRooms(){
        $query= \common\models\Room::find()->alias('r')
            ->select('r.id,r.status,r.number,rt.name as type_name')
            ->leftJoin(RoomType::tableName().' rt','r.type=rt.id')
            ->where(['r.mch_id'=>$this->mchId,'r.premises_id'=>$this->premisesId])
            ->orderBy('r.number asc');
        if($this->type>0){
            $query->andWhere(['r.type'=>$this->type]);
        }
        return $query->asArray()->all();
    }

    /**
     * 处理房间列表字段
     * @param array $rooms
     * @return array
     */
    protected function handleRooms(array $rooms){
        $res=[];
        foreach ($rooms as $room){
            $room['status']=$this->getStatusOfRoom($room);
            if($this->status>=0){
                if($room['status']==$this->status){
                    $res[]=$this->handleRoom($room);
                }
            }else{
                $res[]=$this->handleRoom($room);
            }
        }
        return $res;
    }

    /**
     * 处理房间字段
     * @param array $room
     * @return array
     */
    protected function handleRoom(array $room){
        return [
            'id'=>intval($room['id']),
            'status'=>intval($room['status']),
            'number'=>$room['number'],
            'typeName'=>$room['type_name']
        ];
    }

    /**
     * 获取房间状态
     * @param $room
     * @return int
     */
    protected function getStatusOfRoom($room){
        $status=intval($room['status']);
        if($status==0){
            if($this->hasOccupancy($room['id'])){
                return self::STATUS_OCCUPANCY;
            }else if($this->hasReserve($room['id'])){
                return self::STATUS_RESERVE;
            }else{
                return self::STATUS_EMPTY;
            }
        }else{
            return $status;
        }
    }

    /**
     * 判断是否入住
     * @param $roomId
     * @return bool
     */
    protected function hasOccupancy($roomId){
        $res=OrderRoom::find()->alias('oo')
            ->leftJoin(Order::tableName().' o','oo.order_id=o.id')
            ->where(['oo.room_id'=>$roomId])
            ->andWhere('oo.status=:occupancy and o.status=:normalOrder ',[':occupancy'=>OrderRoom::STATUS_OCCUPANCY,':normalOrder'=>Order::STATUS_NORMAL])
            ->andWhere(':time between oo.start_time and oo.end_time',[':time'=>$this->time])
            ->asArray()->one();
        return !empty($res);
    }

    /**
     * 是否预定
     * @param $roomId
     * @return bool
     */
    protected function hasReserve($roomId){
        $res=OrderRoom::find()->alias('oo')
            ->leftJoin(Order::tableName().' o','oo.order_id=o.id')
            ->where(['oo.room_id'=>$roomId])
            ->andWhere('oo.status=:reverse and o.status=:normalOrder ',[':reverse'=>OrderRoom::STATUS_REVERSE,':normalOrder'=>Order::STATUS_NORMAL])
            ->andWhere(':time between oo.start_time and oo.end_time',[':time'=>$this->time])
            ->asArray()->one();
        return !empty($res);
    }
}