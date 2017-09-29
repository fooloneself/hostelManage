<?php
namespace app\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use service\admin\Login;

class AdminController extends Controller{

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