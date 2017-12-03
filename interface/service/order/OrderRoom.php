<?php
namespace service\order;
use common\components\Server;
use common\models\Room;

class OrderRoom extends Server{
    protected $order;
    protected $orderRoom;
    protected $room;
    public function __construct(Order $order,Room $room)
    {
        $this->order=$order;
        $this->room=$room;
    }

    /**
     * 通过房间实例化
     * @param Order $order
     * @param \common\models\Room $room
     * @return null|static
     */
    public static function byRoom(Order $order,Room $room){
        return new static($order,$room);
    }

    /**
     * 实例化-新增
     * @param Order $order
     * @return static
     */
    public static function newOne(Order $order){
        $orderRoom=new \common\models\OrderRoom();
        return new static($order,$orderRoom);
    }

    public function reverse($start,$end){

    }


}