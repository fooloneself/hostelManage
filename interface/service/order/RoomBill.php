<?php
namespace service\order;
use common\components\Server;
use common\models\OrderCostDetail;
use common\models\OrderRoom;

class RoomBill extends Server{
    protected $room;
    protected $bill;
    protected $totalAmount=0;
    protected $startTime;
    protected $endTime;
    protected $type;
    public function __construct(Room $room)
    {
        $this->room=$room;
    }

    /**
     * 设置清单列表
     * @param array $bills
     * @return $this
     */
    public function setList(array $bills){
        $this->bill=$bills;
        return $this;
    }

    public function getList(){
        return $this->bill;
    }
    /**
     * 生成账单数据模型
     * @param $roomId
     * @param $timestamp
     * @param $amount
     * @return OrderCostDetail
     */
    protected static function newBillModel($roomId,$timestamp,$amount){
        $date=date('Y-m-d',$timestamp);
        list($year,$month,$day)=explode('-',$date);
        $date=str_replace('-','',$date);
        $model=new OrderCostDetail();
        $model->date=intval($date);
        $model->year=intval($year);
        $model->month=intval($month);
        $model->day=intval($day);
        $model->amount=floatval($amount);
        $model->room_id=$roomId;
        return $model;
    }

    /**
     * 生成消费清单--钟点
     * @param Room $room
     * @param $start
     * @param $end
     * @param int $totalAmount
     * @return static
     */
    public static function generateHoursBill(Room $room,$start,$end,$totalAmount=-1){
        $totalAmount=$totalAmount<0 ? $room->getHourPrice()*ceil(($end-$start)/3600) : $totalAmount;
        return self::newRoomBill($room,$totalAmount,[self::newBillModel($room->getId(),$start,$totalAmount)],OrderRoom::TYPE_CLOCK);
    }

    /**
     * 实例化单房间的消费清单
     * @param Room $room
     * @param $totalAmount
     * @param $bills
     * @return static
     */
    protected static function newRoomBill(Room $room,$totalAmount,$bills){
        $roomBill=new static($room);
        $roomBill->setList($bills);
        $roomBill->setTotalAmount($totalAmount);
        return $roomBill;
    }

    /**
     * 生成消费清单--整天
     * @param Room $room
     * @param $start
     * @param $end
     * @param int $totalAmount
     * @return RoomBill
     */
    public static function generateDaysBill(Room $room,$start,$end,$totalAmount=-1){
        $days=ceil(($end-$start)/86400);
        $timestamp=$start;
        $bills=[];
        $roomId=$room->getId();
        if($totalAmount>=0){
            $price=$totalAmount/$days;
            for($i=0;$i<$days;$i++){
                $bills[]=self::newBillModel($roomId,$timestamp,$price);
                $timestamp+=86400;
            }
        }else{
            $totalAmount=0;
            $dayPrices=$room->getPricesOfDay($start,$end);
            $weekPrices=$room->getPricesOfWeek();
            for($i=0;$i<$days;$i++){
                $date=date('Y/m/d',$timestamp);
                if(isset($dayPrices[$date])){
                    $cost=$dayPrices[$date];
                }else if(!empty($weekPrices)){
                    $week=intval(date('w',$timestamp));
                    if($weekPrices[$week]>=0){
                        $cost=$weekPrices[$week];
                    }else{
                        $cost=$room->getDefaultPrice();
                    }
                }else{
                    $cost=$room->getDefaultPrice();
                }
                $bills[]=self::newBillModel($roomId,$timestamp,$cost);
                $totalAmount+=$cost;
                $timestamp+=86400;
            }
        }
        return self::newRoomBill($room,$totalAmount,$bills,OrderRoom::TYPE_DAY);
    }

    /**
     * 设置总金额
     * @param $amount
     * @return $this
     */
    public function setTotalAmount($amount){
        $this->totalAmount=$amount;
        return $this;
    }

    /**
     * 获取总金额
     * @return int
     */
    public function getTotalAmount(){
        return $this->totalAmount;
    }

    /**
     * 插入
     * @param Order $order
     * @return bool
     */
    public function insert(Order $order){
        foreach ($this->bill as $bill){
            $bill->order_id=$order->getId();
            if(!$bill->insert(false)){
                return false;
            }
        }
        return true;
    }

    /**
     * 获取清单开始时间
     * @return mixed
     */
    public function getStartTime(){
        return $this->startTime;
    }

    /**
     * 获取清单结束时间
     * @return mixed
     */
    public function getEndTime(){
        return $this->endTime;
    }

    /**
     * 房间消费类型
     * @return mixed
     */
    public function getType(){
        return $this->type;
    }
}