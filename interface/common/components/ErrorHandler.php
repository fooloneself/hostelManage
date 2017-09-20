<?php
namespace app\common\components;
use common\components\ErrorManager;
use yii\web\NotFoundHttpException;

class ErrorHandler extends \yii\base\ErrorHandler {

    public function renderException($exception){
        if($exception instanceof NotFoundHttpException)
            \Yii::$app->responseHelper->error(ErrorManager::ERROR_INTERFACE_UN_OPEN)->response(true);
        else
            \Yii::$app->responseHelper->error(ErrorManager::ERROR_INTERFACE_ERROR)->response(true);
    }
}