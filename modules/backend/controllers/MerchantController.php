<?php
namespace modules\backend\controllers;
use common\components\Controller;

class MerchantController extends Controller{

    public function actionModules(){
        return \Yii::$app->responseHelper->success(\Yii::$app->user->getAdmin()->allModuleLabels())->response();
    }
}