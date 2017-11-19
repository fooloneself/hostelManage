<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\models\Dictionary;
use common\models\DictionaryItem;

class DictionaryController extends Controller{

    /**
     * 所有订单异常
     * @return mixed
     */
    public function actionAllOrderAbnormal(){
        $abnormal=DictionaryItem::find()
            ->select('key,value')
            ->where(['code'=>Dictionary::DICTIONARY_ORDER_ABNORMAL])
            ->asArray()->all();
        return \Yii::$app->responseHelper->success($abnormal)->response();
    }
}