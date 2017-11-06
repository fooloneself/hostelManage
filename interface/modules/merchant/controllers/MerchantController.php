<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Feedback;
use common\models\MerchantSet;
use service\merchant\Merchant;

class MerchantController extends Controller {
    /**
    * 添加商户信息
    * @return mixed
    */
    public function actionBaseInfoSetting(){
        $admin=\Yii::$app->user->getAdmin();
        //商户名称
        $name=\Yii::$app->requestHelper->post('name','','string');
        //联系人
        $contactName=\Yii::$app->requestHelper->post('contactName','','string');
        //联系方式
        $mobile=\Yii::$app->requestHelper->post('mobile','','string');
        //门店地址
        $address=\Yii::$app->requestHelper->post('address','','string');
        //介绍
        $introduce=\Yii::$app->requestHelper->post('introduce','','string');
        if(!$admin->isAdminOfMerchant()){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_ACCOUNT_ERROR,'非商户管理账户')->response();
        }else if( empty($name) || empty($contactName) || empty($mobile) || empty($address)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        $merchant=$admin->getMerchant();
        if($merchant==false){
            $merchant=new Merchant(new \common\models\Merchant());
        }
        $merchant->put([
            'city'=>0,
            'type'=>0,
            'name'=>$name,
            'mobile'=>$mobile,
            'telephone'=>'',
            'address'=>$address,
            'introduce'=>$introduce,
            'contact_name'=>$contactName
        ]);
        $transaction=\Yii::$app->db->beginTransaction();
        if($merchant->save()==false){
            $transaction->rollBack();
            return \Yii::$app->responseHelper->error($merchant->getError())->response();
        }
        if($admin->bindMerchant($merchant)==false){
            $transaction->rollBack();
            return \Yii::$app->responseHelper->error($admin->getError())->response();
        }
        $transaction->commit();
        return \Yii::$app->responseHelper->success()->response();
    }

    /**
     * 查看商户配置
     * @return mixed
     */
    public function actionConfig(){
        $merchant=\Yii::$app->user->getAdmin()->getMerchant();
        if($merchant){
            $merchant=$merchant->show();
            $res=[];
            $res['base']=[
                'name'=>$merchant['name'],
                'contactName'=>$merchant['contact_name'],
                'mobile'=>$merchant['mobile'],
                'address'=>$merchant['address'],
                'introduce'=>$merchant['introduce']
            ];
            $setting=MerchantSet::findOne(['mch_id'=>$merchant['id']]);
            if($setting){
                $res['setting']=[
                    'orderAutoClose'=>intval($setting->auto_close_switch),
                    'reserveSwitch'=>intval($setting->reserve_switch),
                    'hourRoomSwitch'=>intval($setting->hour_room_switch),
                    'checkOutTime'=>$setting->check_out_time,
                    'hourRoomRange'=>[$setting->hour_room_start_time,$setting->hour_room_end_time],
                    'reserveRetentionTime'=>intval($setting->reserve_retention_time),
                ];
            }
        }else{
            $res=null;
        }
        return \Yii::$app->responseHelper->success($res)->response();
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
        if($orderAutoClose==1 && empty($checkOutTime)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }else if($reserveSwitch==1 && $reserveRetentionTime<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }else if($hourRoomSwitch==1 && (empty($hourRoomStartTime) || empty($hourRoomEndTime)) && $hourRoomStartTime>$hourRoomEndTime){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $checkOutTime=$orderAutoClose===1?$checkOutTime:'';
        $reserveRetentionTime=$reserveSwitch===1?$reserveRetentionTime:0;
        if(!$hourRoomSwitch){
            $hourRoomEndTime=$hourRoomEndTime='';
        }
        $checkOutTime=$orderAutoClose===1?$checkOutTime:'';
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $model=MerchantSet::findOne(['mch_id'=>$mchId]);
        if(!$model)$model=new MerchantSet();
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
        if($model->save()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL,json_encode($model->getErrors()))->response();
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
            'feedback_admin_id'=>\Yii::$app->user->getAdminId(),
            'status'=>Feedback::STATUS_HANDLING
        ]);
        if($model->insert()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
        }
    }
}