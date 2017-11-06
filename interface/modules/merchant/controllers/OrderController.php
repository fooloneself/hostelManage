<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;

class OrderController extends Controller{

    /**
     * 下单
     * @return mixed
     */
    public function actionPlace(){
        $rooms=\Yii::$app->requestHelper->post('rooms',[],'array');
        $IDNumber=\Yii::$app->requestHelper->post('ID','','number');
        $startTime=\Yii::$app->requestHelper->post('startTime',0,'int');
        $endTime=\Yii::$app->requestHelper->post('endTime',0,'int');
        if(empty($rooms) ||empty($IDNumber) || empty($startTime) || empty($endTime)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
    }
}