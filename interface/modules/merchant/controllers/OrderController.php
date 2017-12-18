<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Channel;
use common\models\Dictionary;
use common\models\DictionaryItem;
use common\models\Guest;
use common\models\MerchantMember;
use common\models\MerchantMemberRankDivide;
use common\models\OccupancyRecord;
use common\models\Order;
use common\models\OrderCostDetail;
use common\models\OrderPayDetail;
use common\models\OrderRoom;
use common\models\RoomType;
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
        $pays=\Yii::$app->requestHelper->post('pay',[],'array');
        $mobile=\Yii::$app->requestHelper->post('mobile','','string');
        $name=\Yii::$app->requestHelper->post('name','','string');
        $lodgers=\Yii::$app->requestHelper->post('lodgers',[],'array');
        $channel=\Yii::$app->requestHelper->post('channel',0,'int');
        $type=\Yii::$app->requestHelper->post('type',1,'int');
        $quantity=\Yii::$app->requestHelper->post('quantity',0,'int');
        $totalAmount=\Yii::$app->requestHelper->post('totalAmount',-1,'int');
        $mark=\Yii::$app->requestHelper->post('mark','');
        $order=\service\order\Order::newOne($merchant);
        if(empty($mobile) || empty($name)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $lodgers[]=['mobile'=>$mobile,'name'=>$name];
        $transaction=\Yii::$app->db->beginTransaction();
        $res=$order->from($channel)
            ->byGuest($mobile,$name)
            ->mark($mark)->pay($pays)
            ->occupancy($roomId,$type,$quantity,$lodgers,$totalAmount);
        if($res){
            $transaction->commit();
            return \Yii::$app->responseHelper->success()->response();
        }else{
            $transaction->rollBack();
            return \Yii::$app->responseHelper->error($order->getError())->response();
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
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_ORDER_ROOM_PLACE_FAIL)->response();
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
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_ORDER_NOT_EXISTS)->response();
        }
        $res=$order->occupancyPredetermined($roomId,$guest);
        if($res){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_ORDER_ROOM_PLACE_FAIL)->response();
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

    /**
     * 订单活动
     */
    public function actionActivity(){

    }

    /**
     * 房间订单信息
     */
    public function actionRoomInfo(){
        $orderId=\Yii::$app->requestHelper->post('orderId',0,'int');
        $roomId=\Yii::$app->requestHelper->post('roomId',0,'int');
        $orderRoom=OrderRoom::find()
            ->alias('oo')
            ->select('o.amount,o.amount_paid,o.amount_payable,o.amount_deffer,c.name as channel_name,oo.type,
            oo.quantity,o.mark,o.is_reverse,mm.name as guest_name,mm.mobile,mmrd.name as rank_name,r.type as room_type')
            ->leftJoin(Order::tableName().' o','oo.order_id=o.id')
            ->leftJoin(\common\models\Room::tableName().' r','r.id=oo.room_id')
            ->leftJoin(Channel::tableName().' c','c.id=o.channel')
            ->leftJoin(MerchantMember::tableName().' mm','mm.id=o.guest_id')
            ->leftJoin(MerchantMemberRankDivide::tableName().' mmrd','mmrd.id=mm.rank')
            ->where(['o.id'=>$orderId,'oo.room_id'=>$roomId])
            ->asArray()->one();
        if(empty($orderRoom)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $occupancyRecord=OccupancyRecord::find()
            ->alias('ord')
            ->select('ord.*,mm.is_member,mmrd.name as rank_name')
            ->leftJoin(MerchantMember::tableName().' mm','CONVERT(ord.mobile USING utf8) COLLATE utf8_general_ci=mm.mobile')
            ->leftJoin(MerchantMemberRankDivide::tableName().' mmrd','mm.rank=mmrd.id')
            ->where(['ord.order_id'=>$orderId,'ord.room_id'=>$roomId])
            ->asArray()->all();
        $costRecord=OrderCostDetail::find()
            ->alias('ocd')
            ->select('ocd.year,ocd.month,ocd.day,ocd.amount,r.number,rt.name as type_name')
            ->leftJoin(\common\models\Room::tableName().' r','ocd.room_id=r.id')
            ->leftJoin(RoomType::tableName().' rt','r.type=rt.id')
            ->where(['ocd.order_id'=>$orderId,'ocd.room_id'=>$roomId])->asArray()->all();
        $payRecord=OrderPayDetail::find()
            ->alias('opd')
            ->select('opd.amount,expense.value as expense_name,channel.value as channel_name')
            ->leftJoin(DictionaryItem::tableName().' expense','opd.expense_item=expense.key')
            ->leftJoin(DictionaryItem::tableName().' channel','opd.channel=channel.key')
            ->where(['opd.order_id'=>$orderId])->asArray()->all();
        $order=[
            'channel_name'=>$orderRoom['channel_name'],
            'amount'=>$orderRoom['amount'],
            'amount_payable'=>$orderRoom['amount_payable'],
            'amount_paid'=>$orderRoom['amount_paid'],
            'amount_deffer'=>$orderRoom['amount_deffer'],
            'type'=>$orderRoom['type']==1?'整天':'钟点',
            'quantity'=>$orderRoom['quantity'].($orderRoom['type']==1?'晚':'小时'),
            'mark'=>$orderRoom['mark'],
            'guest_name'=>$orderRoom['guest_name'],
            'mobile'=>$orderRoom['mobile'],
            'rank_name'=>empty($orderRoom['rank_name'])?'非会员':$orderRoom['rank_name'],
            'room_type'=>$orderRoom['room_type']
        ];
        foreach ($occupancyRecord as &$record){
            $record['occupancy_date']=date('Y-m-d H:i:s',$record['check_in_time']);
        }
        foreach ($costRecord as &$record){
            $record['date']=$record['year'].'/'.$record['month'].'/'.$record['day'];
        }
        return \Yii::$app->responseHelper->success([
            'order'=>$order,
            'occupancyRecord'=>$occupancyRecord,
            'costRecord'=>$costRecord,
            'payRecord'=>$payRecord
        ])->response();
    }

    public function actionRoomCostList(){

    }

    /**
     * 换房间
     * @return mixed
     */
    public function actionConvertRoom(){
        $fromRoomId=\Yii::$app->requestHelper->post('fromRoomId',0,'int');
        $toRoomId=\Yii::$app->requestHelper->post('toRoomId',0,'int');
        $orderId=\Yii::$app->requestHelper->post('orderId',0,'int');
        $pays=\Yii::$app->requestHelper->post('pays',[],'array');
        $totalAmount=\Yii::$app->requestHelper->post('totalAmount',-1,'int');
        $activeId=\Yii::$app->requestHelper->post('activeId',0,'int');
        $mark=\Yii::$app->requestHelper->post('mark');
        $merchant=\Yii::$app->user->getAdmin()->getMerchant();
        $server=\service\order\Order::byId($merchant,$orderId);
        if(empty($server)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $fromRoom=Room::byId($merchant,$fromRoomId);
        $toRoom=Room::byId($merchant,$toRoomId);
        if(empty($fromRoom) || empty($toRoom)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $server->pay($pays)->mark($mark);
        $transaction=\Yii::$app->db->beginTransaction();
        if($server->changeRoom($fromRoom,$toRoom,time(),$totalAmount)){
            $transaction->commit();
            return \Yii::$app->responseHelper->success()->response();
        }else{
            $transaction->rollBack();
            return \Yii::$app->responseHelper->error($server->getError())->response();
        }
    }

    public function actionCheckOut(){
        $merchant=\Yii::$app->user->getAdmin()->getMerchant();
        $roomId=\Yii::$app->requestHelper->post('roomId',0,'int');
        $orderId=\Yii::$app->requestHelper->post('orderId',0,'int');
        $pays=\Yii::$app->requestHelper->post('pays',[],'array');
        $order=\service\order\Order::byId($merchant,$orderId);
        $room=Room::byId($merchant,$roomId);
        if(empty($order) || empty($room)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $order->pay($pays);
        $transaction=\Yii::$app->db->beginTransaction();
        if($order->checkOut($room)){
            $transaction->commit();
            return \Yii::$app->responseHelper->success()->response();
        }else{
            $transaction->rollBack();
            return \Yii::$app->responseHelper->error($order->getError())->response();
        }
    }
}