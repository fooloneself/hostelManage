<?php
namespace app\controllers;
use common\components\Controller;
use common\components\ErrorManager;

class SiteController extends Controller{

    public function actionCapture(){
        return \Yii::$app->responseHelper->success(rand(1000,9999))->response();
    }
}