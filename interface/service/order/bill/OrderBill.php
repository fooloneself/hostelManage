<?php
namespace service\order\bill;
use common\components\ErrorManager;
use service\order\Order;
use service\order\Room;
use common\models\OrderRoom;
use common\components\Server;
use service\merchant\Merchant;

/**
 * Class OrderBill
 * @package service\order\bill
 */

class OrderBill extends Server{
    /**
     * 房间账单
     * @var array
     */
    private $roomBill=[];
    /**
     * 总消费
     * @var float
     */
    protected $totalAmount=0;
    /**
     * 商户对象
     * @var \service\merchant\Merchant Merchant
     */
    protected $merchant;

    public function __construct(Merchant $merchant)
    {
        $this->merchant=$merchant;
    }

    /**
     * 获取下单房间的消费记录
     * @param Room $room
     * @return RoomBill
     */
    public function getRoomBill(Room $room){
        return isset($this->roomBill[$room->getId()]) ? $this->roomBill[$room->getId()] : null;
    }

    /**
     * 遍历账单
     * @param $func
     */
    public function iterate($func){
        foreach ($this->roomBill as $bill){
            $func($bill);
        }
    }

    /**
     * 删除房间
     * @param Room $room
     */
    public function delBill(Room $room){
        unset($this->roomBill[$room->getId()]);
    }
    /**
     * 添加下单房间的消费记录
     * @param RoomBill $roomBill
     */
    protected function addRoomBill(RoomBill $roomBill){
        $this->totalAmount+=$roomBill->getTotalAmount();
        $this->putRoomBill($roomBill);
    }

    /**
     * 放入订单房间
     * @param RoomBill $roomBill
     */
    protected function putRoomBill(RoomBill $roomBill){
        $this->roomBill[$roomBill->getRoom()->getId()]=$roomBill;
    }
    /**
     * 获取订单总金额
     * @return int
     */
    public function getTotalAmount(){
        return $this->totalAmount;
    }

    /**
     * 生成下单整日房的账单
     * @param Room $room
     * @param $start
     * @param $quantity
     * @return bool
     */
    protected function generateDay(Room $room,$start,$quantity){
        $bill=DayRoomBillGenerator::instance()->generate($room,$start,$quantity);
        if(!$room->canPlaceOrder($bill->getStartTime(),$bill->getEndTime(),OrderRoom::TYPE_CLOCK)){
            $this->setError($room->getError());
            return false;
        }else{
            $this->addRoomBill($bill);
            return true;
        }
    }

    /**
     * 下单钟点房的账单
     * @param Room $room
     * @param $start
     * @param $quantity
     * @return bool
     */
    protected function generateHour(Room $room,$start,$quantity){
        $bill=HourRoomBillGenerator::instance()->generate($room,$start,$quantity);
        if(!$room->canPlaceOrder($bill->getStartTime(),$bill->getEndTime(),OrderRoom::TYPE_CLOCK)){
            $this->setError($room->getError());
            return false;
        }else{
            $this->addRoomBill($bill);
            return true;
        }
    }

    /**
     * 生成订单账单
     * @param array $rooms
     * @return bool
     */
    public function generate(array $rooms){
        foreach ($rooms as $room){
            $roomId=intval($room['roomId']);
            $startTime=intval($room['startTime']);
            $quantity=intval($room['quantity']);
            $type=intval($room['type']);
            $room=Room::byId($this->merchant,$roomId);
            if(empty($room)){
                $this->setError(ErrorManager::ERROR_ROOM_NOT_EXISTS);
                return false;
            }
            if($type==OrderRoom::TYPE_DAY){
                if(!$this->generateDay($room,$startTime,$quantity)){
                    return false;
                }
            }else if($type==OrderRoom::TYPE_CLOCK){
                if(!$this->generateHour($room,$startTime,$quantity)){
                    return false;
                }
            }else{
                $this->setError(ErrorManager::ERROR_ORDER_ROOM_PLACE_TYPE_ERROR);
                return false;
            }
            return true;
        }
    }

    /**
     * 预定
     * @param Order $order
     * @return bool
     */
    public function reverse(Order $order){
        foreach ($this->roomBill as $roomBill){
            if(!$roomBill->reverse($order)){
                $this->setError($roomBill->getError());
                return false;
            }
        }
        return true;
    }

    /**
     * 入住
     * @param Order $order
     * @return bool
     */
    public function occupancy(Order $order){
        foreach ($this->roomBill as $roomBill){
            if(!$roomBill->occupancy($order)){
                $this->setError($roomBill->getError());
                return false;
            }
        }
        return true;
    }

    /**
     * 加载订单
     * @param Order $order
     * @return $this
     */
    public function load(Order $order){
        $this->totalAmount=$order->getAmount();
        $this->loadOrderRoom($order);
        return $this;
    }

    /**
     * 加载订单房间明细
     * @param Order $order
     * @return $this
     */
    protected function loadOrderRoom(Order $order){
        $orderRooms=self::getOrderRooms($order);
        foreach ($orderRooms as $orderRoom){
            $room=Room::byId($order->getMerchant(),$orderRoom->room_id);
            $roomBill=new RoomBill($room,$orderRoom);
            $roomBill->loadBill($order);
            $this->putRoomBill($roomBill);
        }
        return $this;
    }

    /**
     * 读取订单房间
     * @param Order $order
     * @return array|\yii\db\ActiveRecord[]
     */
    protected static function getOrderRooms(Order $order){
        return OrderRoom::find()->where(['order_id'=>$order->getId()])->all();
    }

    /**
     * 减去总消费
     * @param $amount
     */
    public function plusAmount($amount){
        $this->totalAmount-=$amount;
    }

    /**
     * 增加费用
     * @param $amount
     */
    public function addAmount($amount){
        $this->totalAmount+=$amount;
    }
}