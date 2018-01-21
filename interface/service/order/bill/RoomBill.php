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
     * 遍历账单
     * @param $func
     * @return bool
     */
    public function iterateBill($func){
        list($obj,$funcName)=$func;
        foreach ($this->bill as $key=>$bill){
            if($obj->$funcName($bill,$key)===false){
                return false;
            }
        }
        return true;
    }

    /**
     * 新增差价
     * @param $diff
     */
    public function addDiffAmount($diff){
        $this->orderRoom->amount+=$diff;
    }
    /**
     * 异常消费记录
     * @param $index
     * @return bool
     */
    public function removeBill($index){
        $model=$this->bill[$index];
        if($this->delBill($model)){
            unset($this->bill[$index]);
            return true;
        }else{
            return false;
        }
    }

    /**
     * 删除消费记录
     * @param OrderCostDetail $costDetail
     * @return bool
     */
    protected function delBill(OrderCostDetail $costDetail){
        if($costDetail->delete()){
            $this->orderRoom->amount-=$costDetail->amount;
            return true;
        }else{
            $this->setError(ErrorManager::ERROR_DELETE_FAIL);
            return false;
        }
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
       $this->addBill($this->newBillModel($timestamp,$amount));
    }

    /**
     * 添加一个清单
     * @param OrderCostDetail $model
     */
    protected function addBill(OrderCostDetail $model){
        $this->orderRoom->amount += $model->amount;
        $this->bill[]=$model;
    }
    /**
     * 插入
     * @param Order $order
     * @return bool
     */
    protected function save(Order $order){
        foreach ($this->bill as $bill){
            $bill->order_id=$order->getId();
            if(!$bill->save(false)){
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
        if(!$this->save($order)){
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
        if(!$this->save($order)){
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
        return intval($this->orderRoom->start_time);
    }

    /**
     * 获取结束时间
     * @return mixed
     */
    public function getEndTime(){
        return intval($this->orderRoom->end_time);
    }

    /**
     * 获取数量
     * @return int
     */
    public function getQuantity(){
        return intval($this->orderRoom->quantity);
    }
    /**
     * 获取房间总房价
     * @return mixed
     */
    public function getTotalAmount(){
        return floatval($this->orderRoom->amount);
    }

    /**
     * 获取房间对象
     * @return Room
     */
    public function getRoom(){
        return $this->room;
    }

    /**
     * 是否整天
     * @return bool
     */
    public function isDay(){
        return $this->orderRoom->type==OrderRoom::TYPE_DAY;
    }

    /**
     * 是否钟点
     * @return bool
     */
    public function isHour(){
        return $this->orderRoom->type==OrderRoom::TYPE_CLOCK;
    }
    /**
     * 退房
     * @return bool
     */
    public function checkOut(){
        $this->orderRoom->status=OrderRoom::STATUS_CHECK_OUT;
        $this->orderRoom->end_time=$_SERVER['REQUEST_TIME'];
        if($this->orderRoom->update(false)){
            return true;
        }else{
            $this->setError(ErrorManager::ERROR_ORDER_ROOM_CHANGE_FAIL);
            return false;
        }
    }

    /**
     * 续房
     * @param OrderBill $orderBill
     * @param Room $room
     * @param $quantity
     * @return bool
     */
    public function goOn(OrderBill $orderBill,Room $room,$quantity){
        if($this->orderRoom->type==OrderRoom::TYPE_DAY){
            $roomBill=DayRoomBillGenerator::instance()->generate($room,$this->orderRoom->end_time,$quantity);
        }else{
            $roomBill=HourRoomBillGenerator::instance()->generate($room,$this->orderRoom->end_time,$quantity);
        }
        $self=$this;
        $roomBill->iterateBill(function (OrderCostDetail $model)use($self,$orderBill){
            $self->addBill($model);
            $orderBill->addAmount($model->amount);
        });
        $this->orderRoom->end_time=$roomBill->getEndTime();
        return true;
    }

    /**
     * 换房
     * @param OrderBill $bill
     * @param Room $toRoom
     * @param $time
     * @return bool
     */
    public function change(OrderBill $bill,Room $toRoom,$time){

        return true;
    }

    protected function splice($endTime,$func){
        $date=date('Ymd');
        $operate=$this;
        foreach ($this->bill as $orderCostDetail){
            if($roomBill->isDay() && $bill->date>=$date){
                if(!$roomBill->removeBill($index)){
                    $operate->setError($roomBill->getError());
                    return false;
                }else{
                    $orderBill->plusAmount($bill->amount);
                }
            }else if($roomBill->isHour()){
                $diffHour=floor(($roomBill->getEndTime()-time())/6400);
                if($diffHour>0){
                    $diffAmount=($roomBill->getTotalAmount()/$roomBill->getQuantity())*$diffHour;
                    $roomBill->addDiffAmount(-$diffAmount);
                    $bill->amount-=$diffAmount;
                }
            }
        }
        return $roomBill->iterateBill(function (OrderCostDetail $bill,$index)use($operate,$orderBill,$roomBill,$date){

        });
    }
}