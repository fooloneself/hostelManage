<?php
namespace service\order;
use common\components\ErrorManager;
use common\components\Server;
use common\models\Order;
use common\models\OrderRoom;
use service\guest\Guest;
use service\merchant\Merchant;

class OrderManger extends Server{
    protected $merchant;
    protected $guest;
    protected $order;
    protected $room=[];
    public function __construct(Merchant $merchant)
    {
        $this->merchant=$merchant;
    }

    /**
     * 生产订单号
     * @param Merchant $merchant
     * @return string
     */
    protected function makeOrderNo(Merchant $merchant){
        return 'A'.str_pad(base_convert($merchant->getId(),10,16),STR_PAD_LEFT,6).date('ymdHmi').rand(100,999);
    }

    /**
     * 绑定顾客
     * @param $mobile
     * @param $name
     * @return $this
     */
    public function guest($mobile,$name){
        $this->guest=Guest::by($this->merchant,$mobile,$name);
        if(empty($this->guest)){
            $this->setError(ErrorManager::ERROR_GUEST_SAVE_WRONG);
        }
        return $this;
    }

    /**
     * 获取下订单的人
     * @return mixed
     */
    public function getGuest(){
        return $this->guest;
    }

    public function reverse(array $rooms,$channel,$mark=null){
        $this->room=[];
        if(empty($this->guest)){
            return false;
        }
        $this->order=self::instanceOrder($this->merchant,$this->guest,Order::STATUS_REVERSE,$channel,$mark);
        $merchantSet=$this->merchant->getSetting();
        foreach ($rooms as $room){
            $room=Room::byId($this->merchant->getId(),$room[0]);
            if(!$this->checkRoom($room,$room[1],$room[2],$room[3],$merchantSet)){
                return false;
            }
            if($room[3]==OrderRoom::TYPE_CLOCK){
                $cost=$room->calculateHourCost($room[1],$room[2]);
            }else{
                $cost=$room->calculateDayCost($room[1],$room[2]);
            }
            $this->addCost($cost);
            $this->room[]=$room;
        }
        return true;
    }

    public function pay($pay){
        
    }


    protected function addCost($cost){
        $this->order->amount_payable+=$cost;
    }
    /**
     * 校验房间
     * @param Room $room
     * @param $startTime
     * @param $endTime
     * @param $type
     * @param $merchantSet
     * @return bool
     */
    protected function checkRoom(Room $room,$startTime,$endTime,$type,$merchantSet){
        if(!$room->exists()){
            $this->setError(ErrorManager::ERROR_ROOM_NOT_EXISTS);
            return false;
        }else if(!$room->hasSetType() || !$room->canPlaceOrder()){
            $this->setError(ErrorManager::ERROR_ROOM_CANNOT_REVERSE);
            return false;
        }else if($room->hasReserve($startTime,$endTime)){
            $this->setError(ErrorManager::ERROR_ROOM_HAS_RESERVE);
            return false;
        }else if($room->hasOccupancy($startTime,$endTime)){
            $this->setError(ErrorManager::ERROR_ROOM_HAS_OCCUPANCY);
            return false;
        }else if($type==OrderRoom::TYPE_CLOCK && !$room->allowClock($startTime,$endTime,$merchantSet)){
            $this->setError(ErrorManager::ERROR_ROOM_CLOCK_DENY);
            return false;
        }else{
            return true;
        }
    }

    /**
     * 实例化一个订单模型
     * @param Merchant $merchant
     * @param Guest $guest
     * @param $status
     * @param $channel
     * @param $mark
     * @return Order
     */
    protected static function instanceOrder(Merchant $merchant,Guest $guest,$status,$channel,$mark){
        $order=new Order();
        $order->mch_id=$merchant->getId();
        $order->guest_id=$guest->getId();
        $order->place_time=$_SERVER['REQUEST_TIME'];
        $order->order_no=self::makeOrderNo($merchant);
        $order->channel=$channel;
        $order->mark=$mark;
        $order->status=$status;
        return $order;
    }

    /**
     * 获取订单ID
     * @return int
     */
    public function Id(){
        return intval($this->order->getAttribute('id'));
    }
    public function occupancy($room,$orderNo=null){

    }
}
