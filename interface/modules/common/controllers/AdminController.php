<?php
namespace modules\common\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\AdminInfo;

class AdminController extends Controller{
    /**
     * 重置登录密码
     * @return mixed
     */
    public function actionResetPassword(){
        $oldPwd=\Yii::$app->requestHelper->post('oldPassword','','string');
        $newPwd=\Yii::$app->requestHelper->post('newPassword','','string');
        $confirmPwd=\Yii::$app->requestHelper->post('confirmPassword','','string');
        $adminId=\Yii::$app->requestHelper->post('adminId',0,'int');
        if($adminId>0){
            $admin=\service\admin\Admin::byId($adminId);
            if(!$admin->isExists()){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
            }else if($admin->getMchId()!=\Yii::$app->user->getAdmin()->getMchId()){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
            }
        }else{
            $admin=\Yii::$app->user->getAdmin();
        }
        if(empty($oldPwd) || empty($newPwd) || empty($confirmPwd)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }else if($newPwd!=$confirmPwd){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'两次密码输入不一致')->response();
        }else if(!$admin->isEqualToPwd($oldPwd)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PWD_ERROR,'旧密码输入错误')->response();
        }else if($admin->resetPwd($newPwd)){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL,'密码重置失败')->response();
        }
    }

    /**
     * 账号补充信息
     * @return mixed
     */
    public function actionInfoModify(){
        $name=\Yii::$app->requestHelper->post('name','','string');
        $mobile=\Yii::$app->requestHelper->post('mobile','','string');
        $sex=\Yii::$app->requestHelper->post('sex',0,'int');
        $birthday=\Yii::$app->requestHelper->post('birthday',0,'int');
        if(empty($name) || empty($mobile)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        $adminId=\Yii::$app->user->getAdminId();
        $model=AdminInfo::findOne(['admin_id'=>$adminId]);
        if(empty($model)){
            $model=new AdminInfo();
            $model->admin_id=$adminId;
        }
        $model->setAttributes([
            'name'=>$name,
            'mobile'=>$mobile,
            'sex'=>$sex,
            'birthday'=>intval($birthday)
        ]);
        if($model->save()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL)->response();
        }
    }

    /**
     * 查看个人信息
     * @return mixed
     */
    public function actionInfo(){
        $admin=AdminInfo::find()->where(['admin_id'=>\Yii::$app->user->getAdminId()])->asArray()->one();
        if($admin){
            $admin['birthday']=$admin['birthday']>0?date('Y-m-d',$admin['birthday']):'';
        }
        return \Yii::$app->responseHelper->success($admin)->response();
    }
}