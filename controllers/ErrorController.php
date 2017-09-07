<?php
namespace app\controllers;
use common\components\Controller;
use common\components\ErrorManager;

class ErrorController extends Controller{
    /**
     * 统一错误返回接口
     * @return mixed
     */
    public function actionError(){
        return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INTERFACE_UN_OPEN)->response();
    }
}