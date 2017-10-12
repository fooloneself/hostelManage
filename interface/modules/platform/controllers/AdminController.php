<?php
namespace modules\platform\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\library\Helper;
use common\models\Admin;
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
        $admin=\Yii::$app->user->getAdmin();
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
     * 新增账号
     * @return mixed
     */
    public function actionAdd(){
        $userName=\Yii::$app->requestHelper->post('username','','string');
        $password=\Yii::$app->requestHelper->post('password','','string');
        $name=\Yii::$app->requestHelper->post('name','','string');
        $mobile=\Yii::$app->requestHelper->post('mobile','','string');
        $sex=\Yii::$app->requestHelper->post('sex',0,'int');
        $birthday=\Yii::$app->requestHelper->post('birthday',0,'int');
        if(empty($name) || empty($mobile) || empty($userName) || empty($password)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        if(!Helper::checkPwd($password)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PWD_FORMAT)->response();
        }
        $admin=Admin::findOne(['user_name'=>$userName]);
        if($admin){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_USER_EXISTS)->response();
        }
        $admin=new Admin();
        $admin->user_name=$userName;
        $admin->password=Helper::encryptPwd($password);
        if(!$admin->insert()){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL,'账号添加失败')->response();
        }
        $model=new AdminInfo();
        $model->setAttributes([
            'name'=>$name,
            'mobile'=>$mobile,
            'sex'=>$sex,
            'birthday'=>$birthday,
            'admin_id'=>$admin->id
        ]);
        if($model->insert()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL,'账号信息添加失败')->response();
        }
    }
}