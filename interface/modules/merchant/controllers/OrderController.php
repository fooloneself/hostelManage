<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Channel;
use common\models\Dictionary;
use common\models\DictionaryItem;
use common\models\Guest;
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
        return $this->place('occupancy');
    }

    /**
     * 预定
     */
    public function actionReverse(){
        return $this->place('reverse');
    }

    /**
     * 下单
     * @param $operate
     * @return mixed
     */
    protected function place($operate){
        $lodgers=\Yii::$app->requestHelper->post('lodgers',[],'array');
        $guest=\Yii::$app->requestHelper->post('guest',[],'array');
        $roomId=\Yii::$app->requestHelper->post('roomId',0,'int');
        $price=\Yii::$app->requestHelper->post('price',0,'float');
        $mark=\Yii::$app->requestHelper->post('mark','','string');
        $pay=\Yii::$app->requestHelper->post('pay',[],'array');
        $type=\Yii::$app->requestHelper->post('type',1,'int');
        $number=\Yii::$app->requestHelper->post('number',0,'int');
        $channel=\Yii::$app->requestHelper->post('channel');
        $merchant=\Yii::$app->user->getAdmin()->getMerchant();
        $startTime=$_SERVER['REQUEST_TIME'];
        if($type==1){
            $endTime=strtotime(date('Y-m-d',$startTime+86400*$number).' '.$merchant->getSetting()->check_out_time);
        }else{
            $endTime=$startTime+$number;
        }
        $manager=new OrderManger($merchant);
        $transaction=\Yii::$app->db->beginTransaction();
        $lodgers[]=$guest;
        $manager->pay($pay)
            ->room([['roomId'=>$roomId,'price'=>$price,'type'=>$type,'start'=>$startTime,'end'=>$endTime]])
            ->mark($mark,$channel)
            ->lodger($lodgers)
            ->guest($guest['mobile'],$guest['name']);
        if(!$manager->$operate()){
            $transaction->rollBack();
            return \Yii::$app->responseHelper->error($manager->getError())->response();
        }else{
            $transaction->commit();
            return \Yii::$app->responseHelper->success()->response();
        }
    }
    /**
     * 入住-预定
     */
    public function actionOccupancyByReserve(){

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
            ->select('o.id,c.name as channel_name,g.person_name,g.mobile,oo.start_time,oo.end_time,o.amount_payable,amount_deffer,oo.status,r.number,di.value as abnormal')
            ->leftJoin(OrderRoom::tableName().' oo','o.id=oo.order_id')
            ->leftJoin(\common\models\Room::tableName().' r','oo.room_id=r.id')
            ->leftJoin(Guest::tableName().'g','g.id=o.guest_id')
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
            $query->andWhere('g.mobile=:search or g.person_name=:search',[':search'=>$search]);
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
}