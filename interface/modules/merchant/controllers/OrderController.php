<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Guest;
use service\order\Room;

class OrderController extends Controller{

    /**
     * 入住
     * @return mixed
     */
    public function actionOccupancy(){
        $roomId=\Yii::$app->requestHelper->post('roomId',0,'int');
        $IDNumber=\Yii::$app->requestHelper->post('ID','','number');
        $startTime=\Yii::$app->requestHelper->post('startTime',0,'int');
        $endTime=\Yii::$app->requestHelper->post('endTime',0,'int');
        if($roomId<1 ||empty($IDNumber) || empty($startTime) || empty($endTime)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
    }

    /**
     * 预定
     */
    public function actionReserve(){
        $persons=\Yii::$app->requestHelper->post('person',[],'array');
        $orderChannel=\Yii::$app->requestHelper->post('orderChannel','','string');
        $startTime=\Yii::$app->requestHelper->post('startTime',0,'int');
        $endTime=\Yii::$app->requestHelper->post('endTime',0,'int');
        $costInfo=\Yii::$app->requestHelper->post('costInfo',[],'array');
        $roomId=\Yii::$app->requestHelper->post('roomId',0,'int');
        $shouldPay=\Yii::$app->requestHelper->post('shouldPay',0,'float');
        if(empty($persons) || $roomId<1 || empty($startTime) || empty($endTime) || $startTime>$endTime || empty($costInfo || empty($shouldPay))){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $room=Room::byId($mchId,$roomId);
        if(!$room->exists()){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_ROOM_NOT_EXISTS)->response();
        }else if($room->hasReserve($startTime,$endTime)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_ROOM_HAS_RESERVE)->response();
        }else if($room->hasOccupancy($startTime,$endTime)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_ROOM_HAS_OCCUPANCY)->response();
        }
    }

    protected function handleCost(array $costs){

    }

    protected function recordPersons($persons,$mchId){

    }

    /**
     * 入住-预定
     */
    public function actionOccupancyByReserve(){

    }
}