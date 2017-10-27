<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\models\RoomType;
use service\room\Room;

class CheckstandController extends Controller{

    /**
     * 房间列表
     * @return mixed
     */
    public function actionRoom(){
        $time=\Yii::$app->requestHelper->post('time',0,'int');
        $time=$time>0?$time:intval($_SERVER['REQUEST_TIME']);
        $status=\Yii::$app->requestHelper->post('status',0,'int');
        $type=\Yii::$app->requestHelper->post('type',-1,'int');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $premiseId=\Yii::$app->user->getAdmin()->getMerchant()->getPremise()->id;
        $rooms=new Room($mchId,$premiseId);
        $rooms->time=$time;
        $rooms->type=$type;
        $rooms->status=$status;
        return \Yii::$app->responseHelper->success($rooms->get())->response();
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

    public function actionRoomInfo(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $roomId=\Yii::$app->requestHelper->post('roomId',0,'int');
        $time=\Yii::$app->requestHelper->post('')
    }
}