<?php
namespace modules\merchant\controllers;
use common\components\Controller;

class MerchantController extends Controller{

    public function actionModules(){
        return \Yii::$app->responseHelper->success(\Yii::$app->user->getAdmin()->allModuleLabels())->response();
    }
}