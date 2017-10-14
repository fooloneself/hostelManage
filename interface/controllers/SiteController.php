<?php
namespace app\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Dictionary;
use common\models\DictionaryItem;

class SiteController extends Controller{

    public function actionCapture(){
        return \Yii::$app->responseHelper->success(rand(1000,9999))->response();
    }

    /**
     * 获取所有性别
     * @return mixed
     */
    public function actionSex(){
        $list=DictionaryItem::find()
            ->where(['code'=>Dictionary::DICTIONARY_SEX])
            ->orderBy('order asc')
            ->asArray()->all();
        return \Yii::$app->responseHelper->success($list)->response();
    }
}