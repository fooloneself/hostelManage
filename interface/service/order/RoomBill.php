<?php
namespace service\order;
use common\components\ErrorManager;
use common\components\Server;
use common\models\OrderCostDetail;
use common\models\OrderRoom;

class RoomBill extends Server{
    protected $room;
    protected $bill;
    protected $totalAmount=0;
    protected $quantity;
    protected $startTime;
    protected $endTime;
    protected $type;
    public function __construct(Room $room)
    {
        $this->room=$room;
    }

    /**
     * 多少天
     * @param $type
     * @param $quantity
     * @return $this
     */
    public function days($quantity){
        $this->type=OrderRoom::TYPE_DAY;
        $this->quantity=$quantity;
        $this->startTime=$_SERVER['REQUEST_TIME'];
        $this->endTime=strtotime(date('Y-m-d',$this->startTime+$quantity*86400).' '.$this->room->getMerchant()->getSetting()->check_out_time);
        return $this;
    }

    /**
     * 多少小时
     * @param $quantity
     * @return $this
     */
    public function hours($quantity){
        $this->type=OrderRoom::TYPE_CLOCK;
        $this->quantity=$quantity;
        $this->startTime=$_SERVER['REQUEST_TIME'];
        $this->endTime=$this->startTime+$quantity*3600;
        return $this;
    }

    public function duringHour($start,$end){
        $this->type=OrderRoom::TYPE_CLOCK;
        $this->quantity=($end-$start)/3600;
        $this->startTime=strtotime(date('Y-m-d').' '.date('H:i:s',$start));
        $this->endTime=strtotime(date('Y-m-d').' '.date('H:i:s',$end));
    }

    public function duringDay($start,$end){
        $this->type=OrderRoom::TYPE_DAY;
        $this->quantity=($end-$start)/86400+1;
        $checkOutTime=$this->room->getMerchant()->getSetting()->check_out_time;
        $this->startTime=strtotime(date('Y-m-d',$start).' '.$checkOutTime);
        $this->endTime=strtotime(date('Y-m-d',$end+86400).' '.$checkOutTime);
        return $this;
    }
    /**
     * 获取清单
     * @return mixed
     */
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
    public function generateHoursBill($totalAmount=-1){
        $totalAmount=$totalAmount<0 ? $this->room->getHourPrice()*$this->quantity : $totalAmount;
        $this->totalAmount=$totalAmount;
        $this->bill=[self::newBillModel($this->room->getId(),$this->startTime,$totalAmount)];
        return $this;
    }

    /**
     * 实例化单房间的消费清单
     * @param Room $room
     * @param $start
     * @param $end
     * @param $totalAmount
     * @param $bills
     * @return static
     */
    public static function byRoom(Room $room){
        return new static($room);
    }

    /**
     * 生成消费清单--整天
     * @param Room $room
     * @param $start
     * @param $end
     * @param int $totalAmount
     * @return RoomBill
     */
    public function generateDaysBill($totalAmount=-1){
        $timestamp=$this->startTime;
        $bills=[];
        $roomId=$this->room->getId();
        if($totalAmount>=0){
            $price=$totalAmount/$this->quantity;
            for($i=0;$i<$this->quantity;$i++){
                $bills[]=self::newBillModel($roomId,$timestamp,$price);
                $timestamp+=86400;
            }
        }else{
            $totalAmount=0;
            $dayPrices=$this->room->getPricesOfDay($this->startTime,$this->endTime);
            $weekPrices=$this->room->getPricesOfWeek();
            for($i=0;$i<$this->quantity;$i++){
                $date=date('Y/m/d',$timestamp);
                if(isset($dayPrices[$date])){
                    $cost=$dayPrices[$date];
                }else if(!empty($weekPrices)){
                    $week=intval(date('w',$timestamp));
                    if($weekPrices[$week]>=0){
                        $cost=$weekPrices[$week];
                    }else{
                        $cost=$this->room->getDefaultPrice();
                    }
                }else{
                    $cost=$this->room->getDefaultPrice();
                }
                $bills[]=self::newBillModel($roomId,$timestamp,$cost);
                $totalAmount+=$cost;
                $timestamp+=86400;
            }
        }
        $this->bill=$bills;
        $this->totalAmount=$totalAmount;
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
                $this->setError(ErrorManager::ERROR_ORDER_BILL_INSERT_FAIL);
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