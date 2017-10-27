<?php
namespace modules\platform\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Dictionary;
use common\models\DictionaryItem;
use service\Pager;

/**
 * 管理字典
 * Class DictionaryController
 * @package modules\admin\controllers
 */
class DictionaryController extends Controller{

    /**
     * 字典新增、修改
     * @return mixed
     */
    public function actionRecord(){
        $label=\Yii::$app->requestHelper->post('label');
        $code=\Yii::$app->requestHelper->post('code');
        $introduce=\Yii::$app->requestHelper->post('introduce','');
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        if(empty($label) || empty($code))
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        if($id>0){
            $model=Dictionary::findOne(['id'=>$id]);
            if(empty($model)){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'未找到字典')->response();
            }
            if($model->code!=$code){
                $d=Dictionary::findOne(['code'=>$code]);
                if($d){
                    return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DICTIONARY_KEY_EXISTS)->response();
                }
            }
            $model->setAttributes(['code'=>$code,'label'=>$label,'introduce'=>$introduce]);
            if($model->save(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
            }
        }else{
            $model=Dictionary::findOne(['code'=>$code]);
            if($model){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DICTIONARY_KEY_EXISTS)->response();
            }
            $model=new Dictionary();
            $model->setAttributes(['code'=>$code,'label'=>$label,'introduce'=>$introduce]);
            if($model->insert(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
            }
        }
    }
    /**
     * 删除
     * @return mixed
     */
    public function actionDelete(){
        $code=\Yii::$app->requestHelper->post('code');
        if(empty($code))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        $model=Dictionary::findOne(['code'=>$code]);
        if($model){
            if($model->delete()==false){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_FAIL)->response();
            }
        }
        return \Yii::$app->responseHelper->success()->response();
    }

    /**
     * 分页查看
     * @return mixed
     */
    public function actionList(){
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $query=Dictionary::find();
        list($count,$list)=Pager::instance($query,$pageSize)->get($page);
        return \Yii::$app->responseHelper->success([
            'total'=>$count,
            'list'=>$list
        ])->response();
    }

    /**
     * 查看单个字典
     * @return mixed
     */
    public function actionView(){
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        if($id<=0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        $dictionary=Dictionary::find()->where(['id'=>$id])->asArray()->one();
        return \Yii::$app->responseHelper->success($dictionary)->response();
    }

    /**
     * 通过code获取字典名称
     * @return mixed
     */
    public function actionViewByCode(){
        $code=\Yii::$app->requestHelper->post('code');
        if(empty($code)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        $dictionary=Dictionary::find()->where(['code'=>$code])->asArray()->one();
        return \Yii::$app->responseHelper->success($dictionary)->response();
    }
    /**
     * 数据项新增、修改
     * @return mixed
     */
    public function actionItemRecord(){
        $code=\Yii::$app->requestHelper->post('code');
        $key=\Yii::$app->requestHelper->post('key');
        $value=\Yii::$app->requestHelper->post('value');
        $order=\Yii::$app->requestHelper->post('order');
        $id=\Yii::$app->requestHelper->post('id');
        if(empty($code) || !isset($key) || empty($value) || !isset($order)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        if($id>0){
            $model=DictionaryItem::findOne(['id'=>$id]);
            if(empty($model))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'未找到数据项')->response();
            if($code!=$model->code && DictionaryItem::findOne(['key'=>$key,'code'=>$model->code])){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DICTIONARY_KEY_EXISTS)->response();
            }
            $model->setAttributes(['key'=>$key,'value'=>$value,'order'=>$order,'code'=>$code]);
            if($model->update(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL)->response();
            }
        }else{
            $model=DictionaryItem::findOne(['code'=>$code,'key'=>$key]);
            if($model){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DICTIONARY_KEY_EXISTS)->response();
            }
            $model=new DictionaryItem();
            $model->setAttributes(['code'=>$code,'key'=>$key,'value'=>$value,'order'=>$order]);
            if($model->insert(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
            }
        }
    }

    /**
     * 删除数据项
     * @return mixed
     */
    public function actionDeleteItem(){
        $id=\Yii::$app->requestHelper->post('id');
        if($id<=0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $model=DictionaryItem::findOne(['id'=>$id]);
        if($model && !$model->delete()){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_FAIL)->response();
        }else{
            return \Yii::$app->responseHelper->success()->response();
        }
    }

    /**
     * 数据项列表
     * @return mixed
     */
    public function actionItemList(){
        $code=\Yii::$app->requestHelper->post('code');
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $query=DictionaryItem::find()
            ->alias('di')
            ->select('di.*,d.label')
            ->leftJoin(Dictionary::tableName().' d','d.code=di.code')
            ->where(['di.code'=>$code])
            ->orderBy('di.order asc');
        list($count,$list)=Pager::instance($query,$pageSize)->get($page);
        return \Yii::$app->responseHelper->success([
            'totalCount'=>$count,
            'list'=>$list
        ])->response();
    }

    /**
     * 单个数据项
     * @return mixed
     */
    public function actionItemView(){
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        if($id<=0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        $item=DictionaryItem::find()->where(['id'=>$id])->asArray()->one();
        return \Yii::$app->responseHelper->success($item)->response();
    }
}