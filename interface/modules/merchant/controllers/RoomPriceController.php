<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Room;
use common\models\RoomDayPrice;
use common\models\RoomWeekPrice;

class RoomPriceController extends Controller{

    /**
     * 设置房间周价格
     * @return mixed
     */
    public function actionWeekPrice(){
        $monday=\Yii::$app->requestHelper->post('monday');
        $tuesday=\Yii::$app->requestHelper->post('tuesday');
        $wensday=\Yii::$app->requestHelper->post('wensday');
        $thursday=\Yii::$app->requestHelper->post('thursday');
        $friday=\Yii::$app->requestHelper->post('friday');
        $saturday=\Yii::$app->requestHelper->post('saturday');
        $sunday=\Yii::$app->requestHelper->post('sunday');
        $room=\Yii::$app->requestHelper->post('room');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $room=Room::findOne(['id'=>$room,'mch_id'=>$mchId]);
        if(empty($room)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_NOT_PRIVILEGE)->response();
        }
        $model=RoomWeekPrice::findOne(['room_id'=>$room]);
        if(empty($model)){
            $model=new RoomWeekPrice();
            $model->room_id=$room->id;
        }
        $model->monday=$monday;
        $model->tuesday=$tuesday;
        $model->wensday=$wensday;
        $model->thursday=$thursday;
        $model->friday=$friday;
        $model->saturday=$saturday;
        $model->sunday=$sunday;
        if($model->save()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_OPERATE_FAIL)->response();
        }
    }

    /**
     * 设置房间日价格
     * @return mixed
     */
    public function actionDayPrice(){
        $room=\Yii::$app->requestHelper->post('room');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $room=Room::findOne(['id'=>$room,'mch_id'=>$mchId]);
        if(empty($room)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_NOT_PRIVILEGE)->response();
        }
        $year=\Yii::$app->requestHelper->post('year');
        $month=\Yii::$app->requestHelper->post('month');
        $day=\Yii::$app->requestHelper->post('day');
        $price=\Yii::$app->requestHelper->post('price');
        $dayPrice=RoomDayPrice::findOne(['year'=>$year,'month'=>$month,'day'=>$day]);
        if(empty($dayPrice)){
            $dayPrice=new RoomDayPrice();
            $dayPrice->room_id=$room->id;
            $dayPrice->year=$year;
            $dayPrice->month=$month;
            $dayPrice->day=$day;
        }
        $dayPrice->price=$price;
        if($dayPrice->save()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_OPERATE_FAIL)->response();
        }
    }
}