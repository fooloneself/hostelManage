<?php
namespace service\order;
use common\components\Server;
use common\models\Order;

class OrderRoom extends Server{
    protected $room;
    protected $startTime;
    protected $endTime;
    protected $bill;
    protected $type;
    public function __construct(Room $room)
    {
        $this->room=$room;
    }

    /**
     * 通过房间实例化
     * @param \common\models\Room $room
     * @return null|static
     */
    public static function newOne(Room $room){
        return new static($room);
    }

    /**
     * 时间段
     * @param $start
     * @param $end
     * @param int $type
     * @return $this
     */
    public function during($start,$end,$type=\common\models\OrderRoom::TYPE_DAY){
        $this->startTime=$start;
        $this->endTime=$end;
        $this->type=$type;
        return $this;
    }

    /**
     * 预定
     * @param \service\order\Order $order
     * @return bool
     */
    public function reverse(\service\order\Order $order){
        return $this->insertRecord($order,\common\models\OrderRoom::STATUS_REVERSE);
    }

    /**
     * 入住
     * @param \service\order\Order $order
     * @return bool
     */
    public function occupancy(\service\order\Order $order){
        return $this->insertRecord($order,\common\models\OrderRoom::STATUS_OCCUPANCY);
    }

    /**
     * 生成消费清单
     * @param $totalAmount
     * @return RoomBill|static
     */
    public function generateBill($totalAmount){
        if($this->type==\common\models\OrderRoom::TYPE_DAY){
            $this->bill=RoomBill::generateDaysBill($this->room,$this->startTime,$this->endTime,$totalAmount);
        }else{
            $this->bill=RoomBill::generateHoursBill($this->room,$this->startTime,$this->endTime,$totalAmount);
        }
        return $this->bill;
    }

    /**
     * 插入数据
     * @param Order $order
     * @param $status
     * @return bool
     */
    protected function insert(\service\order\Order $order,$status){
        $model=new \common\models\OrderRoom();
        $model->order_id=$order->getId();
        $model->start_time=$this->startTime;
        $model->end_time=$this->endTime;
        $model->status=$status;
        $model->room_id=$this->room->getId();
        $model->amount=$this->bill->getTotalAmount();
        $model->type=$this->type;
        return $model->insert(false);
    }

    /**
     * 保存记录
     * @param Order $order
     * @param $status
     * @return bool
     */
    protected function insertRecord(\service\order\Order $order,$status){
        if($this->insert($order,$status) && $this->bill->insert($order)){
            return true;
        }else{
            return false;
        }
    }
}