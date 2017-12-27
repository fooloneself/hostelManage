<?php
namespace service\order\bill;
use common\components\ErrorManager;
use common\components\Server;
use common\models\OrderCostDetail;
use common\models\OrderRoom;
use service\order\Room;

class RoomBill extends Server {
    protected $room;
    private $orderRoom;
    protected $quantity=0;
    protected $totalAmount;
    protected $bill=[];

    public function __construct(Room $room,OrderRoom $orderRoom)
    {
        $this->room=$room;
        $this->orderRoom=$orderRoom;
        $this->orderRoom->room_id=$room->getId();
    }

    /**
     * 加载消费记录
     * @param Order $order
     */
    public function loadBill(Order $order){
        $this->bill=OrderCostDetail::find()->where(['order_id'=>$order->getId(),'room_id'=>$this->room->getId()])->all();
    }

    /**
     * 生成清单模型
     * @param $timestamp
     * @param $amount
     * @return OrderCostDetail
     */
    protected function newBillModel($timestamp,$amount){
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
        return $model;
    }

    /**
     * 新增消费记录
     * @param $timestamp
     * @param $amount
     */
    public function generateBill($timestamp,$amount){
       $this->addDayBill($this->newBillModel($timestamp,$amount));
    }

    /**
     * 添加一个清单
     * @param OrderCostDetail $model
     */
    protected function addBill(OrderCostDetail $model){
        $this->totalAmount+=$model->amount;
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
     * 获取开始时间
     * @return mixed
     */
    public function getStartTime(){
        return $this->orderRoom->start_time;
    }

    /**
     * 获取结束时间
     * @return mixed
     */
    public function getEndTime(){
        return $this->orderRoom->end_time;
    }

    /**
     * 获取数量
     * @return int
     */
    public function getQuantity(){
        return $this->orderRoom->quantity;
    }
    /**
     * 获取房间总房价
     * @return mixed
     */
    public function getTotalAmount(){
        return $this->totalAmount;
    }

    /**
     * 获取房间对象
     * @return Room
     */
    public function getRoom(){
        return $this->room;
    }
}