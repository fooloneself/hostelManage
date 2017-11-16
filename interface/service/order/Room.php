<?php
namespace service\order;
use common\components\ErrorManager;
use common\components\Server;
use common\models\OccupancyRecord;
use common\models\Order;
use common\models\OrderCostDetail;
use common\models\OrderOccupancyRoom;
use common\models\OrderReserveRoom;
use common\models\OrderRoom;
use common\models\RoomDayPrice;
use common\models\RoomType;
use common\models\RoomWeekPrice;
use service\merchant\Merchant;

class Room extends Server {
    //房间
    protected $room;
    protected $roomType;
    //开始时间
    protected $startTime;
    //结束时间
    protected $endTime;
    //类型 1天 2钟点
    protected $type;
    //状态 0撤销 1预定 2入住 3退房
    protected $status;
    //总费用
    protected $amount=0;
    //费用详情
    protected $costBill=[];
    //商户
    protected $merchant;

    protected $price;
    public function __construct(\common\models\Room $room,Merchant $merchant)
    {
        $this->room=$room;
        $this->roomType=RoomType::findOne(['id'=>$room->type,'mch_id'=>$room->mch_id]);
        $this->merchant=$merchant;
    }

    /**
     * 房间是否存在
     * @return bool
     */
    public function exists(){
        return !empty($this->room);
    }

    /**
     * 设置价格
     * @param $price
     * @return $this
     */
    public function price($price){
        $this->price=$price;
        return $this;
    }
    /**
     * 是否已经设置分类
     * @return bool
     */
    public function hasSetType(){
        return !empty($this->roomType);
    }

    /**
     * 房间是否可下订单
     * @return bool
     */
    public function canPlaceOrder(){
        return $this->room->status==\common\models\Room::STATUS_CAN_ORDER;
    }

    /**
     * 获取房间ID
     * @return int
     */
    public function getRoomId(){
        return $this->room->id;
    }

    /**
     * 是否是小时收费
     * @return bool
     */
    public function isOrderFoHour(){
        return $this->type==OrderRoom::TYPE_CLOCK?true: false;
    }

    /**
     * 获取已下订单
     * @return null|\common\models\OrderRoom
     */
    protected function getHavePlaced(){
        $order=OrderRoom::find()->alias('oo')
            ->leftJoin(Order::tableName().' o','o.id=oo.order_id')
            ->where(['o.mch_id'=>$this->room->mch_id,'oo.room_id'=>$this->room->id])
            ->andWhere('o.status<>:orderStatus and oo.status<>:orderRoomStatus',[
                ':orderStatus'=>Order::STATUS_CANCEL,
                ':orderRoomStatus'=>OrderRoom::STATUS_CANCEL
            ])
            ->andWhere('(oo.start_time>:startTime and oo.start_time<:endTime) or (oo.end_time>:startTime and oo.end_time<:endTime)',[
                ':startTime'=>$this->startTime,':endTime'=>$this->endTime
            ])
            ->andWhere('oo.start_time>=:startTime and oo.end_time<=:endTime',[
                ':startTime'=>$this->startTime,':endTime'=>$this->endTime
            ])
            ->one();
        return $order;
    }

    /**
     * 是否允许钟点房
     * @param int $startTime
     * @param int $endTime
     * @param null|\common\models\MerchantSet  $mchSetting
     * @return bool
     */
    public function allowClock($mchSetting){
        if(empty($mchSetting) || $mchSetting->auto_close_switch!=1 || !$this->roomType->allow_hour_room){
            return false;
        }else if(($this->startTime>$mchSetting->hour_room_start_time && $this->startTime<$mchSetting->hour_room_end_time)){
            return false;
        }else if($this->endTime>$mchSetting->hour_room_start_time && $this->endTime<$mchSetting->hour_room_end_time){
            return false;
        }else if($this->startTime<=$mchSetting->hour_room_start_time && $this->endTime>=$mchSetting->hour_room_end_time){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 计算钟点房费用
     * @return float
     */
    protected function calculateHourCost(){
        if($this->price>0){
            $price=$this->price;
        }else{
            $price=$this->roomType->hour_room_price;
        }
        return $price*ceil(($this->endTime-$this->startTime)/3600);
    }

    /**
     * 计算按天算房费
     * @return float
     */
    protected function calculateDayCost(){
        $days=($this->endTime-$this->startTime)/86400;
        if($this->price>0){
            return $days*$this->price;
        }
        $startWeek=date('w',$this->startTime);
        $cycle=floor($days/7);
        $surplus=$days%7;
        $weekNum=array_fill(1,7,$cycle);
        for($key=0;$key<$surplus;$key++){
            $weekNum[$startWeek+$key]++;
        }
        $dayPriceList=RoomDayPrice::getDayPriceList($this->room->mch_id,$this->room->type,$this->startTime,$this->endTime);
        $totalCost=0;
        foreach ($dayPriceList as $dayPrice){
            $totalCost+=floatval($dayPrice['price']);
            $weekNum[$dayPrice['week']]--;
        }
        $weekPrice=$this->getPriceOfWeek();
        $defaultPriceNum=0;
        if(empty($weekPrice)){
            $defaultPriceNum=array_sum($weekNum);
        }else{
            foreach ($weekPrice as $sub=>$price){
                if($price<0){
                    $defaultPriceNum+=$weekNum[$sub+1];
                }else{
                    $totalCost+=$weekNum[$sub+1]*$price;
                }
            }
        }
        $totalCost+=$defaultPriceNum*floatval($this->roomType->default_price);
        return $totalCost;
    }

    /**
     * 计算费用
     * @return float
     */
    public function calculateCost(){
        if($this->type==OrderRoom::TYPE_DAY){
            $this->amount= $this->calculateDayCost();
        }else{
            $this->amount= $this->calculateHourCost();
        }
        return $this->amount;
    }

    /**
     * 生成消费清单
     * @return $this
     */
    public function generateCostBill(){
        $this->costBill=[];
        if($this->type=OrderRoom::TYPE_DAY){
            $this->amount=$this->generateDayCostBill();
        }else{
            $this->amount=$this->generateHourCostBill();
        }
        return $this->amount;
    }

    /**
     * 生成费用记录-钟点房
     * @return float    总费用
     */
    protected function generateHourCostBill(){
        $cost=$this->calculateHourCost();
        $this->addCostRecord($this->startTime,$cost);
        return $cost;
    }

    /**
     * 生成消费记录-天
     * @return float|int|mixed 总费用
     */
    protected function generateDayCostBill(){
        $days=($this->endTime-$this->startTime)/86400;
        $totalCost=0;
        $timestamp=$this->startTime;
        if($this->price>0){
            for($i=0;$i<$days;$i++){
                $timestamp+=$i*86400;
                $this->addCostRecord($timestamp,$this->price);
            }
            $totalCost=$days*$this->price;
        }else{
            $dayPrices=$this->getPricesOfDay();
            $weekPrices=$this->getPricesOfWeek();
            for($i=0;$i<$days;$i++){
                $timestamp+=$i*86400;
                $date=intval(date('Ymd',$timestamp));
                if(isset($dayPrices[$date])){
                    $cost=$dayPrices[$date];
                }else if(!empty($weekPrices)){
                    $week=intval(date('w',$timestamp));
                    if($weekPrices[$week]>=0){
                        $cost=$weekPrices[$week];
                    }else{
                        $cost=floatval($this->roomType->getAttribute('default_price'));
                    }
                }else{
                    $cost=floatval($this->roomType->getAttribute('default_price'));
                }
                $this->addCostRecord($timestamp,$cost);
                $totalCost+=$cost;
            }
        }
        return $totalCost;
    }

    /**
     * 获取单日价格
     * @return array
     */
    protected function getPricesOfDay(){
        $dayPriceList=RoomDayPrice::getDayPriceList($this->room->mch_id,$this->room->type,$this->startTime,$this->endTime);
        $prices=[];
        foreach ($dayPriceList as $dayPrice){
            $prices[$dayPrice['date']]=floatval($dayPrice['price']);
        }
        return $prices;
    }

    /**
     * 获取周价格
     * @return array
     */
    protected function getPricesOfWeek(){
        $prices=RoomWeekPrice::find()
            ->where(['type_id'=>$this->room->type,'mch_id'=>$this->room->mch_id])
            ->asArray()->one();
        if(empty($prices)){
            return [];
        }else{
            return [
                floatval($prices['monday']),
                floatval($prices['tuesday']),
                floatval($prices['wensday']),
                floatval($prices['thursday']),
                floatval($prices['friday']),
                floatval($prices['saturday']),
                floatval($prices['sunday'])
            ];
        }
    }
    /**
     * 添加消费记录
     * @param $timestamp
     * @param $amount
     */
    protected function addCostRecord($timestamp,$amount){
        $date=date('Y-m-d',$timestamp);
        list($year,$month,$day)=explode('-',$date);
        $date=intval(str_replace('-','',$date));
        $this->costBill[]=[
            'date'=>$date,
            'year'=>$year,
            'month'=>$month,
            'day'=>$day,
            'amount'=>$amount
        ];
    }

    /**
     * 费用
     * @return int
     */
    public function getAmount(){
        return $this->amount;
    }
    /**
     * 校验房间是否能预定、入住
     * @return bool
     */
    public function isAllow(){
        if(!$this->exists()){
            $this->setError(ErrorManager::ERROR_ROOM_NOT_EXISTS);
            return false;
        }else if(!$this->hasSetType() || !$this->canPlaceOrder()){
            $this->setError(ErrorManager::ERROR_ROOM_CANNOT_REVERSE);
            return false;
        }else if($this->isOrderFoHour() && !$this->allowClock($this->merchant->getSetting())){
            $this->setError(ErrorManager::ERROR_ROOM_CLOCK_DENY);
            return false;
        }
        $orderRoom=$this->getHavePlaced();
        if(empty($orderRoom)){
            return true;
        }else if($orderRoom->status==OrderRoom::STATUS_REVERSE){
            $this->setError(ErrorManager::ERROR_ROOM_HAS_RESERVE);
            return false;
        }else{
            $this->setError(ErrorManager::ERROR_ROOM_HAS_OCCUPANCY);
            return false;
        }
    }
    /**
     * 通过房间ID实例化类
     * @param Merchant $merchant
     * @param $roomId
     * @return static
     */
    public static function byId(Merchant $merchant,$roomId){
        $room=\common\models\Room::findOne(['mch_id'=>$merchant->getId(),'id'=>$roomId]);
        return new static($room,$merchant);
    }

    /**
     * 下单
     * @param $type
     * @param $status
     * @param $startTime
     * @param $endTime
     * @return $this
     */
    public function place($type,$status,$startTime,$endTime){
        $this->type=$type;
        $this->startTime=$startTime;
        $this->endTime=$endTime;
        $this->status=$status;
        return $this;
    }

    /**
     * 记录消费记录
     * @param $orderId
     * @return bool
     */
    public function insertToDb($orderId){
        $orderRoom=new OrderRoom();
        $orderRoom->order_id=$orderId;
        $orderRoom->room_id=$this->room->id;
        $orderRoom->status=$this->status;
        $orderRoom->start_time=$this->startTime;
        $orderRoom->end_time=$this->endTime;
        $orderRoom->type=$this->type;
        $orderRoom->amount=$this->amount;
        if(!$orderRoom->insert()){
            $this->setError(ErrorManager::ERROR_INSERT_FAIL);
            return false;
        }
        return $this->insertBillToDb($orderId);
    }

    /**
     * 插入消费详情
     * @param $orderId
     * @return bool
     */
    protected function insertBillToDb($orderId){
        $model=new OrderCostDetail();
        foreach ($this->costBill as $record){
            $model->setAttributes($record);
            $model->order_id=$orderId;
            $model->room_id=$this->room->id;
            if(!$model->insert()){
                $this->setError(ErrorManager::ERROR_INSERT_FAIL);
                return false;
            }
        }
        return true;
    }

    /**
     * 登记入住
     * @param $orderId
     * @param array $lodgers
     * @return bool
     */
    public function occupancy($orderId,array $lodgers){
        $orderRoom=OrderRoom::findOne(['order_id'=>$orderId,'room_id'=>$this->room->id]);
        if(empty($orderRoom)){
            $this->setError(ErrorManager::ERROR_ORDER_ROOM_UN_PLACE);
            return false;
        }
        $model=new OccupancyRecord();
        $attr=[
            'order_id'=>$orderId,
            'room_id'=>$this->getRoomId(),
            'mch_id'=>$this->merchant->getId(),
            'premises_id'=>$this->merchant->getPremise()->getId(),
            'check_in_time'=>$_SERVER['REQUEST_TIME'],
            'room_number'=>$this->room->number
        ];
        foreach ($lodgers as $lodger){
            $model->setAttributes($attr);
            $model->mobile=$lodger['mobile'];
            $model->person_name=$lodger['name'];
            $model->setIsNewRecord(true);
            if(!$model->insert()){
                return false;
            }
        }
        if($orderRoom->status!=OrderRoom::STATUS_OCCUPANCY){
            $orderRoom->status=OrderRoom::STATUS_OCCUPANCY;
            if(!$orderRoom->update()){
                $this->setError(ErrorManager::ERROR_ORDER_ROOM_CHANGE_FAIL);
                return false;
            }
        }
        return true;
    }
}