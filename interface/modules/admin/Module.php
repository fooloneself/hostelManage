<?php
namespace modules\admin;
use common\components\ErrorManager;

class Module extends \common\components\Module{
    protected $privilege=[];
    public function beforeAction($action)
    {
        $admin=\Yii::$app->user->getAdmin();
        if(!$admin->isExists()){
            \Yii::$app->responseHelper->error(ErrorManager::ERROR_USER_NOT_EXISTS)->response();
            return false;
        }else if(!$admin->isLogin()){
            \Yii::$app->responseHelper->error(ErrorManager::ERROR_NOT_LOGIN)->response();
            return false;
        }else if($admin->isExpire()){
            \Yii::$app->responseHelper->error(ErrorManager::ERROR_LOGIN_EXPIRE)->response();
            return false;
        }else if($this->checkPrivilege($admin->getPrivilege(),$action)===false){
            \Yii::$app->responseHelper->error(ErrorManager::ERROR_NOT_PRIVILEGE)->response();
            return false;
        }else{
            return parent::beforeAction($action);
        }
    }
}