<?php
namespace service\order\bill;
use common\components\ErrorManager;
use common\components\Server;
use common\models\OrderCostDetail;
use common\models\OrderRoom;
use service\order\place\Order;
use service\order\Room;

abstract class RoomBill extends Server {
    protected $room;
    private $orderRoom;
    protected $quantity=0;
    protected $startTime;
    protected $endTime;
    private $bill=[];
    protected function __construct(Room $room,OrderRoom $orderRoom)
    {
        $this->room=$room;
        $this->orderRoom=$orderRoom;
        $this->orderRoom->room_id=$room->getId();
    }

    /**
     * 新实例
     * @param Room $room
     * @return static
     */
    public static function newOne(Room $room){
        return new static($room,new OrderRoom());
    }

    /**
     * 加载消费记录
     * @param Order $order
     */
    public function loadBill(Order $order){
        $this->bill=OrderCostDetail::find()->where(['order_id'=>$order->getId(),'room_id'=>$this->room->getId()])->all();
    }

    /**
     * 新增消费记录
     * @param $timestamp
     * @param $amount
     */
    protected function addBill($timestamp,$amount){
        $date=date('Y-m-d',$timestamp);
        list($year,$month,$day)=explode('-',$date);
        $date=str_replace('-','',$date);
        $model=new OrderCostDetail();
        $model->date=intval($date);
        $model->year=intval($year);
        $model->month=intval($month);
        $model->day=intval($day);
        $model->amount=floatval($amount);
        $model->room_id=$this->room->getId();
        $this->bill[]=$model;
    }

    /**
     * 插入
     * @param Order $order
     * @return bool
     */
    protected function insert(Order $order){
        foreach ($this->bill as $bill){
            $bill->order_id=$order->getId();
            if(!$bill->insert(false)){
                $this->setError(ErrorManager::ERROR_ORDER_BILL_INSERT_FAIL);
                return false;
            }
        }
        return true;
    }

    /**
     * 预定
     * @param Order $order
     * @return bool
     */
    public function reverse(Order $order){
        if(!$this->insert($order)){
            return false;
        }else if(!$this->saveOrderRoom($order,OrderRoom::STATUS_REVERSE)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 入住
     * @param Order $order
     * @return bool
     */
    public function occupancy(Order $order){
        if(!$this->insert($order)){
            return false;
        }else if(!$this->saveOrderRoom($order,OrderRoom::STATUS_OCCUPANCY)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 保存订单房间的下单信息
     * @param Order $order
     * @param $status
     * @return bool
     */
    protected function saveOrderRoom(Order $order,$status){
        $this->orderRoom->order_id=$order->getId();
        $this->orderRoom->start_time=$this->startTime;
        $this->orderRoom->end_time=$this->endTime;
        $this->orderRoom->quantity=$this->quantity;
        $this->orderRoom->status=$status;
        if($this->orderRoom->save(false)){
            return true;
        }else{
            $this->setError(ErrorManager::ERROR_ORDER_ROOM_UN_PLACE);
            return false;
        }
    }

    /**
     * 生成清单
     * @param OrderBill $orderBill
     * @return mixed
     */
    abstract public function generate(OrderBill $orderBill);

    /**
     * @param $startTime
     * @param $quantity
     * @return static
     */
    abstract public function quantity($startTime,$quantity);
}