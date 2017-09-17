<?php
namespace modules\backend\controllers;
use common\components\Controller;

class PrivilegeController extends Controller{

    public function actionAll(){
        return \Yii::$app->responseHelper->success($this->module->allPrivilege())->response();
    }


}