<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\models\MerchantMember;
use common\models\Order;
use common\models\OrderRoom;
use common\models\RoomType;
use service\room\Room;

class CheckstandController extends Controller{

    /**
     * 房间列表
     * @return mixed
     */
    public function actionRoom(){
        $status=\Yii::$app->requestHelper->post('status',0,'int');
        $type=\Yii::$app->requestHelper->post('type',0,'int');
        $guestId=\Yii::$app->requestHelper->post('guestId',0,'int');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $premiseId=\Yii::$app->user->getAdmin()->getMerchant()->getPremise()->id;
        $rooms=new Room($mchId,$premiseId);
        return \Yii::$app->responseHelper->success($rooms->get($status,$type,$guestId))->response();
    }

    /**
     * 房间过滤器
     * @param $rooms
     * @return mixed
     */
    public function actionRoomFilter(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $roomType=RoomType::find()
            ->select('id,name')
            ->where(['mch_id'=>$mchId])
            ->asArray()->all();
        return \Yii::$app->responseHelper->success([
            'types'=>$roomType,
            'date'=>date('Y-m-d')
        ])->response();
    }

    /**
     * 查询预订、入住的房客
     * @return mixed
     */
    public function actionOrderGuest(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $guests=Order::find()->alias('o')
            ->select('mm.id,mm.name')
            ->leftJoin(OrderRoom::tableName().' oo','o.id=oo.order_id')
            ->leftJoin(MerchantMember::tableName().' mm','mm.id=o.guest_id')
            ->where(['o.status'=>Order::STATUS_NORMAL,'o.mch_id'=>$mchId])
            ->andWhere(':time between oo.start_time and oo.end_time',[':time'=>$_SERVER['REQUEST_TIME']])
            ->groupBy('mm.id')
            ->asArray()->all();
        return \Yii::$app->responseHelper->success($guests)->response();
    }

    public function actionRoomInfo(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $roomId=\Yii::$app->requestHelper->post('roomId',0,'int');
        $time=\Yii::$app->requestHelper->post('');
    }
}