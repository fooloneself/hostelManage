<?php
namespace app\controllers;
use common\components\Controller;
use common\components\ErrorManager;

class ErrorController extends Controller{

    public function actionError(){
        return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INTERFACE_UN_OPEN)->response();
    }
}