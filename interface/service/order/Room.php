<?php
namespace service\order;
use common\components\Server;
use common\models\OrderRoom;
use common\models\RoomDayPrice;
use common\models\RoomType;
use common\models\RoomWeekPrice;
use service\merchant\Merchant;

class Room extends Server{
    protected $merchant;
    protected $room;
    protected $roomType;
    public function __construct(Merchant $merchant,\common\models\Room $room,RoomType $roomType=null)
    {
        $this->merchant=$merchant;
        $this->room=$room;
        $this->roomType=$roomType;
    }

    /**
     * 实例化
     * @param Merchant $merchant
     * @param $id
     * @return null|static
     */
    public static function byId(Merchant $merchant,$id){
        $room=\common\models\Room::findOne(['id'=>$id,'mch_id'=>$merchant->getId()]);
        if(empty($room)){
            return null;
        }
        $roomType=RoomType::findOne(['id'=>$room->type]);
        return new static($merchant,$room,$roomType);
    }

    /**
     * 获取房间的默认价格
     * @return float
     */
    public function getDefaultPrice(){
        return floatval($this->roomType->default_price);
    }

    /**
     * 获取房间钟点单价
     * @return float
     */
    public function getHourPrice(){
        return floatval($this->roomType->hour_room_price);
    }
    /**
     * 获取房间id
     * @return int
     */
    public function getId(){
        return intval($this->room->id);
    }

    /**
     * 获取商户
     * @return Merchant
     */
    public function getMerchant(){
        return $this->merchant;
    }

    /**
     * 获取单日价格
     * @param $start
     * @param $end
     * @return array
     */
    public function getPricesOfDay($start,$end){
        $dayPriceList=RoomDayPrice::getDayPriceList($this->room->mch_id,$this->room->type,$start,$end);
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
    public function getPricesOfWeek(){
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
}