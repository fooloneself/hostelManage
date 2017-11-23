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

    /**
     * 支付渠道
     * @return mixed
     */
    public function actionPaymentChannel(){
        $abnormal=DictionaryItem::find()
            ->select('key,value')
            ->where(['code'=>Dictionary::DICTIONARY_PAYMENT_CHANNEL])
            ->asArray()->all();
        return \Yii::$app->responseHelper->success($abnormal)->response();
    }

    /**
     * 消费款项
     * @return mixed
     */
    public function actionExpanseItem(){
        $abnormal=DictionaryItem::find()
            ->select('key,value')
            ->where(['code'=>Dictionary::DICTIONARY_EXPANSE_ITEM])
            ->asArray()->all();
        return \Yii::$app->responseHelper->success($abnormal)->response();
    }

    /**
     * 证件号类型
     * @return mixed
     */
    public function actionNumberTypes(){
        $numberType=DictionaryItem::find()->where(['code'=>Dictionary::DICTIONARY_NUMBER_TYPE])->asArray()->all();
        return \Yii::$app->responseHelper->success($numberType)->response();
    }
}