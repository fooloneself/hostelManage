<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\LinkageMenu;
use common\models\Premises;

/**
 * 商户经营场所
 * Class PremisesController
 * @package modules\merchant\controllers
 */
class PremisesController extends Controller{

    /**
     * 添加经营场所
     * @return mixed
     */
    public function actionAdd(){
        $merchantId=\Yii::$app->user->getAdmin()->getMerchant()->getId();
        $city=\Yii::$app->requestHelper->post('city',0,'int');
        $floor=\Yii::$app->requestHelper->post('floor',0,'int');
        $longitude=\Yii::$app->requestHelper->post('longitude',0,'float');
        $latitude=\Yii::$app->requestHelper->post('latitude',0,'float');
        $name=\Yii::$app->requestHelper->post('name','','string');
        $address=\Yii::$app->requestHelper->post('address','','string');
        $street=\Yii::$app->requestHelper->post('street','','string');
        $number=\Yii::$app->requestHelper->post('number',0,'float');
        $introduce=\Yii::$app->requestHelper->post('introduce','','string');
        if($city<1 || empty($name) || (empty($address))){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        $c=LinkageMenu::findOne(['type'=>LinkageMenu::TYPE_REGION,'id'=>$city]);
        if(empty($c))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'未找到地区')->response();
        unset($c);
        $model=new Premises();
        $model->setAttributes([
            'mch_id'=>$merchantId,
            'create_time'=>time(),
            'city'=>$city,
            'floor'=>$floor,
            'number'=>$number,
            'longitude'=>$longitude,
            'latitude'=>$latitude,
            'name'=>$name,
            'address'=>$address,
            'street'=>$street,
            'introduce'=>$introduce
        ]);
        if($model->insert()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL,$model->getErrors())->response();
        }
    }
}