<?php
namespace app\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Admin;

class ErrorController extends Controller{

    public function actionError(){
        return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INTERFACE_UN_OPEN)->response();
    }

    public function actionTest(){
        $admin=Admin::findOne(['id'=>6]);
        var_dump($admin->save());
    }
}