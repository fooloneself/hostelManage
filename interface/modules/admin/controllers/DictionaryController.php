<?php
namespace modules\admin\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Dictionary;

/**
 * 管理字典
 * Class DictionaryController
 * @package modules\admin\controllers
 */
class DictionaryController extends Controller{

    /**
     * 新增
     * @return mixed
     */
    public function actionAdd(){
        $label=\Yii::$app->requestHelper->post('label');
        $key=\Yii::$app->requestHelper->post('key');
        $value=\Yii::$app->requestHelper->post('value');
        if(empty($label) || empty($key) || empty($value))
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        $model=Dictionary::findOne(['key'=>$key]);
        if($model)
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DICTIONARY_KEY_EXISTS)->response();
        $model=new Dictionary();
        $model->setAttributes(['key'=>$key,'value'=>$value,'label'=>$label]);
        if($model->insert(false)){
            return \Yii::$app->responseHelper->success($model->id)->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
        }
    }

    /**
     * 删除
     * @return mixed
     */
    public function actionDelete(){
        $key=\Yii::$app->requestHelper->post('key');
        if(empty($key))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        $model=Dictionary::findOne(['key'=>$key]);
        if($model){
            if($model->delete()==false){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_FAIL)->response();
            }
        }
        return \Yii::$app->responseHelper->success()->response();
    }

    /**
     * 修改
     * @return mixed
     */
    public function actionUpdate(){
        $key=\Yii::$app->requestHelper->post('key');
        $label=\Yii::$app->requestHelper->post('label');
        $value=\Yii::$app->requestHelper->post('value');
        if(empty($key) || (empty($label) && empty($value)))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        $model=Dictionary::findOne(['key'=>$key]);
        if(empty($model))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DICTIONARY_KEY_NOT_EXISTS)->response();
        if(!empty($label))$model->setAttribute('label',$label);
        if(!empty($value))$model->setAttribute('value',$value);
        if($model->save(false)==false){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL)->response();
        }
        return \Yii::$app->responseHelper->success()->response();
    }

    /**
     * 分页查看
     * @return mixed
     */
    public function actionView(){
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $search=\Yii::$app->requestHelper->post('search');
        $query=Dictionary::find();
        if(!empty($search))$query->where(['or',['like','label',$search],['like','key',$search]]);
        $count=intval($query->count());
        $data=$query->offset(($page-1)*$pageSize)->limit($pageSize)->asArray()->orderBy('key asc')->all();
        return \Yii::$app->responseHelper->success([
            'total'=>$count,
            'list'=>$data
        ])->response();
    }
}