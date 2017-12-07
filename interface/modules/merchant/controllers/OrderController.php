<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Channel;
use common\models\Dictionary;
use common\models\DictionaryItem;
use common\models\Guest;
use common\models\MerchantMember;
use common\models\Order;
use common\models\OrderRoom;
use service\order\OrderManger;
use service\order\Room;
use service\Pager;

class OrderController extends Controller{

    /**
     * 入住
     * @return mixed
     */
    public function actionOccupancy(){
        $merchant=\Yii::$app->user->getAdmin()->getMerchant();
        $roomId=\Yii::$app->requestHelper->post('roomId',0,'int');
        $activeId=\Yii::$app->requestHelper->post('activeId',0,'int');
        $pays=\Yii::$app->requestHelper->post('pays',[],'array');
        $mobile=\Yii::$app->requestHelper->post('mobile','','string');
        $name=\Yii::$app->requestHelper->post('name','','string');
        $guest=\Yii::$app->requestHelper->post('guest',[],'array');
        $channel=\Yii::$app->requestHelper->post('channel',0,'int');
        $type=\Yii::$app->requestHelper->post('type',1,'int');
        $number=\Yii::$app->requestHelper->post('number',0,'int');
        $totalAmount=\Yii::$app->requestHelper->post('totalAmount',-1,'int');
        $mark=\Yii::$app->requestHelper->post('mark','');
        $order=\service\order\Order::newOne($merchant);
        $start=$_SERVER['REQUEST_TIME'];
        if($type==OrderRoom::TYPE_DAY){
            $unitQuantity=86400;
        }else{
            $unitQuantity=3600;
        }
        $end=$start+$unitQuantity*$number;
        $res=$order->from($channel)
            ->byGuest($mobile,$name)
            ->mark($mark)->pay($pays)
            ->occupancy($roomId,$type,$start,$end,$guest,$totalAmount);
        if($res){
            return \Yii::$app->requestHelper->success()->response();
        }else{
            return \Yii::$app->requestHelper->error(ErrorManager::ERROR_ORDER_ROOM_PLACE_FAIL)->response();
        }
    }

    /**
     * 预定
     */
    public function actionReverse(){
        $merchant=\Yii::$app->user->getAdmin()->getMerchant();
        $channel=\Yii::$app->requestHelper->post('channel',0,'int');
        $mobile=\Yii::$app->requestHelper->post('mobile','','string');
        $name=\Yii::$app->requestHelper->post('name','','string');
        $rooms=\Yii::$app->requestHelper->post('rooms',[],'array');
        $totalAmount=\Yii::$app->requestHelper->post('totalAmount',-1,'int');
        $activeId=\Yii::$app->requestHelper->post('activeId',0,'int');
        $pays=\Yii::$app->requestHelper->post('pays',[],'array');
        $mark=\Yii::$app->requestHelper->post('mark','');
        $order=\service\order\Order::newOne($merchant);
        $res=$order->byGuest($mobile,$name)
            ->from($channel)
            ->pay($pays)
            ->mark($mark)
            ->reverse($rooms,$totalAmount);
        if($res){
            return \Yii::$app->requestHelper->success()->response();
        }else{
            return \Yii::$app->requestHelper->error(ErrorManager::ERROR_ORDER_ROOM_PLACE_FAIL)->response();
        }
    }

    /**
     * 帮里入住
     * @return mixed
     */
    public function actionOccupancyPredetermined(){
        $merchant=\Yii::$app->user->getAdmin()->getMerchant();
        $roomId=\Yii::$app->requestHelper->post('roomId',0,'int');
        $orderId=\Yii::$app->requestHelper->post('orderId',0,'int');
        $guest=\Yii::$app->requestHelper->post('guest',[],'array');
        $order=\service\order\Order::byId($merchant,$orderId);
        if(empty($order)){
            return \Yii::$app->requestHelper->error(ErrorManager::ERROR_ORDER_NOT_EXISTS)->response();
        }
        $res=$order->occupancyPredetermined($roomId,$guest);
        if($res){
            return \Yii::$app->requestHelper->success()->response();
        }else{
            return \Yii::$app->requestHelper->error(ErrorManager::ERROR_ORDER_ROOM_PLACE_FAIL)->response();
        }
    }

    /**
     * 订单列表
     * @return mixed
     */
    public function actionList(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $typeId=\Yii::$app->requestHelper->post('typeId',0,'int');
        $roomId=\Yii::$app->requestHelper->post('roomId',0,'int');
        $search=\Yii::$app->requestHelper->post('search','','string');
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $channel=\Yii::$app->requestHelper->post('channel',0,'int');
        $checkIn=\Yii::$app->requestHelper->post('checkIn',0,'int');
        $checkOut=\Yii::$app->requestHelper->post('checkOut',-1,'int');
        $abnormal=\Yii::$app->requestHelper->post('abnormal',0,'int');
        $isNormal=\Yii::$app->requestHelper->post('isNormal',1,'int');
        $query=Order::find()->alias('o')
            ->select('o.id,c.name as channel_name,mm.name as person_name,mm.mobile,oo.start_time,oo.end_time,o.amount_payable,amount_deffer,oo.status,r.number,di.value as abnormal')
            ->leftJoin(OrderRoom::tableName().' oo','o.id=oo.order_id')
            ->leftJoin(\common\models\Room::tableName().' r','oo.room_id=r.id')
            ->leftJoin(MerchantMember::tableName().' mm','mm.id=o.guest_id')
            ->leftJoin(Channel::tableName().' c','c.id=o.channel')
            ->leftJoin(DictionaryItem::tableName().' di','di.key=o.abnormal_type and di.code=:code',[':code'=>Dictionary::DICTIONARY_ORDER_ABNORMAL])
            ->where(['o.mch_id'=>$mchId]);
        if($typeId>0){
            $query->andWhere(['r.type'=>$typeId]);
        }
        if($roomId>0){
            $query->andWhere(['oo.room_id'=>$roomId]);
        }
        if(!empty($search)){
            $query->andWhere('mm.mobile=:search or mm.person_name=:search',[':search'=>$search]);
        }
        if($channel>0){
            $query->andWhere(['o.channel'=>$channel]);
        }
        if($checkIn==0){
            $query->andWhere(['from_unixtime(oo.start_time,\'%Y%m%d\')'=>date('Ymd')]);
        }else if($checkIn>0){
            $query->andWhere(['from_unixtime(oo.start_time,\'%Y%m%d\')'=>date('Ymd',$checkIn)]);
        }
        if($checkOut==0){
            $query->andWhere(['from_unixtime(oo.end_time,\'%Y%m%d\')'=>date('Ymd')]);
        }else if($checkOut>0){
            $query->andWhere(['from_unixtime(oo.end_time,\'%Y%m%d\')'=>date('Ymd',$checkOut)]);
        }
        if(!$isNormal){
            $query->andWhere(['o.status'=>Order::STATUS_ABNORMAL]);
        }
        if(!empty($abnormal)){
            $query->andWhere(['o.abnormal_type'=>$abnormal]);
        }
        $query->andWhere('oo.status<>:cancel',[':cancel'=>OrderRoom::STATUS_CANCEL]);
        return $this->getOrderList($query,$page,$pageSize);
    }

    /**
     * 获取订单列表
     * @param $query
     * @param $page
     * @param $pageSize
     * @return mixed
     */
    protected function getOrderList($query,$page,$pageSize){
        list($count,$list)=Pager::instance($query,$pageSize)->get($page);
        $res=[];
        foreach ($list as $item){
            $time=($item['status']==OrderRoom::STATUS_REVERSE || $item['status']==OrderRoom::STATUS_OCCUPANCY) ?
                $item['start_time']:$item['end_time'];
            $res[]=[
                'id'=>intval($item['id']),
                'personName'=>$item['person_name'],
                'mobile'=>$item['mobile'],
                'date'=>date('Y-m-d',$time),
                'channelName'=>$item['channel_name'],
                'number'=>$item['number'],
                'amountPayable'=>floatval($item['amount_payable']),
                'amountDeffer'=>floatval($item['amount_deffer']),
                'abnormal'=>$item['abnormal']
            ];
        }
        return \Yii::$app->responseHelper->success([
            'totalCount'=>$count,
            'list'=>$res
        ])->response();
    }

    public function actionInfo(){
        
    }

    public function actionRoomCostList(){

    }
}