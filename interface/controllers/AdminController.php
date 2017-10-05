<?php
namespace app\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Admin;
use service\admin\Login;
use service\admin\Register;

class AdminController extends Controller{

    /**
     * 注册
     * @return mixed
     */
    public function actionRegister(){
        $userName=\Yii::$app->requestHelper->post('username');
        $password=\Yii::$app->requestHelper->post('password');
        $code=\Yii::$app->requestHelper->post('code');
        if(empty($userName) || empty($password) || empty($code))
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        $admin=new Admin();
        $admin->user_name=$userName;
        $admin->password=$password;
        $admin->mch_id=-1;
        $admin->last_login_time=time();
        $admin->is_super=1;
        $register=new Register($admin);
        if(($admin=$register->exe())===false){
            return \Yii::$app->responseHelper->error($register->getError())->response();
        }
        $admin->resetToken();
        return \Yii::$app->responseHelper->success($admin->getToken())->response();
    }

    /**
     * 注册
     * @return mixed
     */
    public function actionLogin(){
        $userName=\Yii::$app->requestHelper->post('userName');
        $password=\Yii::$app->requestHelper->post('password');
        $code=\Yii::$app->requestHelper->post('code');
        if(empty($userName) || empty($password) || empty($code)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        $login=new Login($userName,$password);
        $admin=$login->exe();
        if($admin===false){
            return \Yii::$app->responseHelper->error($login->getError())->response();
        }else{
            $success=[
                'id'=>$admin->getAdminId(),
                'token'=>$admin->getToken(),
                'privilege'=>$admin->getPrivilege()->all()
            ];
            if($admin->isAdminOfMerchant()){
                $success['merchant']=$admin->getMerchant()->show();
            }
            return \Yii::$app->responseHelper->success($success)->response();
        }
    }
}