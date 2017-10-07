<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Feedback;
use common\models\Merchant;
use common\models\MerchantSet;

class MerchantController extends Controller{

    /**
     * 添加商户信息
     * @return mixed
     */
    public function actionBaseInfoSetting(){
        $admin=\Yii::$app->user->getAdmin();
        $city=\Yii::$app->requestHelper->post('city',0,'int');
        $name=\Yii::$app->requestHelper->post('name','','string');
        $contactName=\Yii::$app->requestHelper->post('contactName','','string');
        $address=\Yii::$app->requestHelper->post('address','','string');
        $type=\Yii::$app->requestHelper->post('type',0,'int');
        $mobile=\Yii::$app->requestHelper->post('mobile','','string');
        $telephone=\Yii::$app->requestHelper->post('telephone','','string');
        $introduce=\Yii::$app->requestHelper->post('introduce','','string');
        if(!$admin->isAdminOfMerchant()){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_ACCOUNT_ERROR,'非商户管理账户')->response();
        }else if($admin->hasBindMerchant()){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_ACCOUNT_ERROR,'已绑定商户')->response();
        }else if($city<1 || empty($name) || empty($address)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        $model=new Merchant();
        $model->setAttributes([
            'city'=>$city,
            'type'=>$type,
            'name'=>$name,
            'mobile'=>$mobile,
            'telephone'=>$telephone,
            'address'=>$address,
            'introduce'=>$introduce,
            'contact_name'=>$contactName
        ]);
        $merchant=new \service\merchant\Merchant($model);
        $transaction=\Yii::$app->db->beginTransaction();
        if($merchant->register()){
            if($admin->bindMerchant($merchant)){
                $transaction->commit();
                return \Yii::$app->responseHelper->success()->response();
            }else{
                $transaction->rollBack();
                return \Yii::$app->responseHelper->error($admin->getError())->response();
            }
        }else{
            $transaction->rollBack();
            return \Yii::$app->responseHelper->error($merchant->getError())->response();
        }
    }

    /**
     * 商户开关设置
     * 自动退房、预定、钟点房
     * @return mixed
     */
    public function actionSetting(){
        //订单自动关闭开关
        $orderAutoClose=\Yii::$app->requestHelper->post('orderAutoClose',0,'int');
        //预定房间开关
        $reserveSwitch=\Yii::$app->requestHelper->post('reserveSwitch',0,'int');
        //钟点房开关
        $hourRoomSwitch=\Yii::$app->requestHelper->post('hourRoomSwitch',0,'int');
        //每日退房时间
        $checkOutTime=\Yii::$app->requestHelper->post('checkOutTime','','string');
        //预定订单保留时间
        $reserveRetentionTime=\Yii::$app->requestHelper->post('reserveRetentionTime',0,'int');
        //钟点房每日开始时间点
        $hourRoomStartTime=\Yii::$app->requestHelper->post('hourRoomStartTime','','string');
        //钟点房每日结束时间点
        $hourRoomEndTime=\Yii::$app->requestHelper->post('hourRoomEndTime','','string');
        if($orderAutoClose===1 && empty($checkOutTime)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }else if($reserveSwitch===1 && $reserveRetentionTime<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }else if($hourRoomSwitch===1 && (empty($hourRoomStartTime) || empty($hourRoomEndTime)) && $hourRoomStartTime>$hourRoomEndTime){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $model=new MerchantSet();
        $model->setAttributes([
            'mch_id'=>\Yii::$app->user->getAdmin()->getMchId(),
            'auto_close_switch'=>$orderAutoClose,
            'reserve_switch'=>$reserveSwitch,
            'hour_room_switch'=>$hourRoomSwitch,
            'reserve_retention_time'=>$reserveRetentionTime,
            'check_out_time'=>$checkOutTime,
            'hour_room_start_time'=>$hourRoomStartTime,
            'hour_room_end_time'=>$hourRoomEndTime
        ]);
        if($model->insert()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
        }
    }

    /**
     * 商家反馈
     * @return mixed
     */
    public function actionFeedback(){
        $info=\Yii::$app->requestHelper->post('feedback','','string');
        if(empty($info))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        $model=new Feedback();
        $model->setAttributes([
            'mch_id'=>\Yii::$app->user->getAdmin()->getMchId(),
            'create_time'=>time(),
            'content'=>$info,
            'status'=>Feedback::STATUS_HANDLING
        ]);
        if($model->insert()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
        }
    }
}