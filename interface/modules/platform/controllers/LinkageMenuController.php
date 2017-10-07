<?php
namespace modules\platform\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\LinkageMenu;

class LinkageMenuController extends Controller{

    /**
     * 查看联动菜单
     * @return mixed
     */
    public function actionView(){
        $pid=\Yii::$app->requestHelper->post('pid',0,'int');
        if($pid<1 && empty($type))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $query=LinkageMenu::find();
        $query->where(['pid'=>$pid]);
        $count=intval($query->count());
        $data=$query->offset(($page-1)*$pageSize)->limit($pageSize);
        return \Yii::$app->responseHelper->success([
            'total'=>$count,
            'list'=>$data
        ])->response();
    }

    /**
     * 新增联动菜单
     * @return mixed
     */
    public function actionAdd(){
        $pid=\Yii::$app->requestHelper->post('pid',0,'int');
        $label=\Yii::$app->requestHelper->post('label');
        if(empty($label))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        if($pid>0){
            $parent=LinkageMenu::findOne(['id'=>$pid]);
            if(empty($parent))return \Yii::$app->responseHelper->error(ERROR_MENU_NOT_EXISTS,'父类菜单不存在')->response();
            $type=$parent->type;
        }else{
            $pid=0;
            $type=\Yii::$app->requestHelper->post('type');
            if(empty($type))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        $model=new LinkageMenu();
        $model->setAttributes(['pid'=>$pid,'label'=>$label,'type'=>$type]);
        if($model->insert()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
        }
    }

    /**
     * 修改菜单
     * @return mixed
     */
    public function actionUpdate(){
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        $label=\Yii::$app->requestHelper->post('label');
        $pid=\Yii::$app->requestHelper->post('pid',-1,'int');
        if($id<1)return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        $model=LinkageMenu::findOne(['id'=>$id]);
        if(empty($model))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_MENU_NOT_EXISTS)->response();
        if($pid>0){
            $parent=LinkageMenu::findOne(['id'=>$pid]);
            if(empty($parent))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_MENU_NOT_EXISTS,'父菜单不存在')->response();
            if($model->type!=$parent->type)return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'菜单不在同一类型')->response();
            $model->pid=$pid;
        }
        if(!empty($label))$model->setAttribute('label',$label);
        if($model->save()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL)->response();
        }
    }

    /**
     * 删除
     * @return mixed
     */
    public function actionDelete(){
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        if($id<1)return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        $model=LinkageMenu::findOne(['id'=>$id]);
        if(empty($model))return \Yii::$app->responseHelper->success()->response();
        $childCount=LinkageMenu::find()->where(['pid'=>$id])->count();
        if($childCount>0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_CANNOT)->response();
        }
        if($model->delete()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_FAIL)->response();
        }
    }
}