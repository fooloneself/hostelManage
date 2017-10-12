<?php
namespace modules\platform\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\LinkageMenu;
use common\models\LinkageMenuItem;

class LinkageMenuController extends Controller{

    /**
     * 查看联动菜单
     * @return mixed
     */
    public function actionView(){
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $query=LinkageMenu::find();
        $count=intval($query->count());
        $data=$query->offset(($page-1)*$pageSize)->limit($pageSize);
        return \Yii::$app->responseHelper->success([
            'total'=>$count,
            'list'=>$data
        ])->response();
    }

    /**
     * 菜单新增、修改
     * @return mixed
     */
    public function actionRecord(){
        $label=\Yii::$app->requestHelper->post('label');
        $code=\Yii::$app->requestHelper->post('code');
        $introduce=\Yii::$app->requestHelper->post('introduce','');
        if(empty($label) || empty($code)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        $id=\Yii::$app->requestHelper->post('id');
        if($id>0){
            $model=LinkageMenu::findOne(['id'=>$id]);
            if(empty($model)){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_MENU_NOT_EXISTS,'未找到菜单')->response();
            }
            if($code!=$model->code){
                if(LinkageMenu::findOne(['code'=>$code])){
                    return \Yii::$app->responseHelper->error(ErrorManager::ERROR_MENU_EXISTS)->response();
                }
            }
            $model->setAttributes(['code'=>$code,'label'=>$label,'introduce'=>$introduce]);
            if($model->save(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL)->response();
            }
        }else{
            $model=LinkageMenu::findOne(['code'=>$code]);
            if($model)return \Yii::$app->responseHelper->error(ErrorManager::ERROR_MENU_EXISTS)->response();
            $model=new LinkageMenu();
            $model->setAttributes(['code'=>$code,'label'=>$label,'introduce'=>$introduce]);
            if($model->insert(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
            }
        }
    }

    /**
     * 删除菜单
     * @return mixed
     */
    public function actionDelete(){
        $id=\Yii::$app->requestHelper->post('id');
        $model=LinkageMenu::findOne(['id'=>$id]);
        if($model && !$model->delete()){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_FAIL)->response();
        }else{
            return \Yii::$app->responseHelper->success()->response();
        }
    }

    /**
     * 删除子菜单
     * @return mixed
     */
    public function actionDeleteItem(){
        $id=\Yii::$app->requestHelper->post('id');
        $model=LinkageMenuItem::findOne(['id'=>$id]);
        $child=LinkageMenuItem::find()->where(['pid'=>$id])->count();
        if($model && ($child>0 ||!$model->delete())){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_FAIL)->response();
        }
        return \Yii::$app->responseHelper->success()->response();
    }
    /**
     * 新增联动菜单
     * @return mixed
     */
    public function actionItemRecord(){
        $pid=\Yii::$app->requestHelper->post('pid',0,'int');
        $label=\Yii::$app->requestHelper->post('label');
        $order=\Yii::$app->requestHelper->post('order');
        $introduce=\Yii::$app->requestHelper->post('introduce','');
        if(empty($label) || !isset($order))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        $id=\Yii::$app->requestHelper->post('id');
        if($id>0){
            $model=LinkageMenuItem::findOne(['id'=>$id]);
            if(empty($model))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_MENU_NOT_EXISTS)->response();
            $model->setAttributes(['introduce'=>$introduce,'label'=>$label,'order'=>$order]);
            if($model->save(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
            }
        }else{
            if($pid>0){
                $parent=LinkageMenuItem::findOne(['id'=>$pid]);
                if(empty($parent))return \Yii::$app->responseHelper->error(ERROR_MENU_NOT_EXISTS,'父类菜单不存在')->response();
                $code=$parent->code;
            }else{
                $pid=0;
                $code=\Yii::$app->requestHelper->post('code');
                if(empty($code))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
            }
            $model=new LinkageMenuItem();
            $model->setAttributes(['pid'=>$pid,'label'=>$label,'code'=>$code,'introduce'=>$introduce,'order'=>$order]);
            if($model->insert(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
            }
        }
    }

    /**
     * 子菜单列表
     * @return mixed
     */
    public function actionItemView(){
        $pid=\Yii::$app->requestHelper->post('pid',0);
        $code=\Yii::$app->requestHelper->post('code');
        $page=\Yii::$app->requestHelper->post('page',0);
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10);
        if(empty($code))return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        $query=LinkageMenuItem::find()
            ->alias('lmi')
            ->select('lmi.*,lmi1.label as parent_label')
            ->leftJoin(LinkageMenuItem::tableName().' lmi1','lmi.pid=lmi1.id')
            ->where(['lmi.code'=>$code,'lmi.pid'=>$pid]);
        $count=$query->count();
        $list=$query->orderBy('lmi.order asc')->limit($pageSize)->offset(($page-1)*$pageSize)->asArray()->all();
        return \Yii::$app->responseHelper->success([
            'total'=>$count,
            'list'=>$list
        ])->response();
    }
}