<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Room;
use common\models\RoomDayPrice;
use common\models\RoomType;
use common\models\RoomWeekPrice;

class RoomPriceController extends Controller{

    /**
     * 设置房间周价格
     * @return mixed
     */
    public function actionRecord(){
        $monday=\Yii::$app->requestHelper->post('monday');
        $tuesday=\Yii::$app->requestHelper->post('tuesday');
        $wensday=\Yii::$app->requestHelper->post('wensday');
        $thursday=\Yii::$app->requestHelper->post('thursday');
        $friday=\Yii::$app->requestHelper->post('friday');
        $saturday=\Yii::$app->requestHelper->post('saturday');
        $sunday=\Yii::$app->requestHelper->post('sunday');
        $typeId=\Yii::$app->requestHelper->post('typeId');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $roomType=RoomType::findOne(['id'=>$typeId,'mch_id'=>$mchId]);
        if(empty($roomType)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_NOT_PRIVILEGE)->response();
        }
        $model=RoomWeekPrice::findOne(['type_id'=>$typeId,'mch_id'=>$mchId]);
        if(empty($model)){
            $model=new RoomWeekPrice();
            $model->type_id=$typeId;
            $model->mch_id=$mchId;
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
        $typeId=\Yii::$app->requestHelper->post('typeId');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $roomType=RoomType::findOne(['id'=>$typeId,'mch_id'=>$mchId]);
        if(empty($roomType)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_NOT_PRIVILEGE)->response();
        }
        $year=\Yii::$app->requestHelper->post('year');
        $month=\Yii::$app->requestHelper->post('month');
        $day=\Yii::$app->requestHelper->post('day');
        $price=\Yii::$app->requestHelper->post('price');
        $dayPrice=RoomDayPrice::findOne(['year'=>$year,'month'=>$month,'day'=>$day,'mch_id'=>$mchId]);
        if(empty($dayPrice)){
            $dayPrice=new RoomDayPrice();
            $dayPrice->type_id=$roomType->id;
            $dayPrice->mch_id=$mchId;
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

    /**
     * 查看房间周价格
     * @return mixed
     */
    public function actionViewWeek(){
        $typeId=\Yii::$app->requestHelper->post('typeId',0,'int');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        if($typeId<=0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        $price=RoomWeekPrice::findOne(['type_id'=>$typeId,'mch_id'=>$mchId]);
        if(!empty($price)){
            $price=$price->getAttributes();
        }
        return \Yii::$app->responseHelper->success($price)->response();
    }
}