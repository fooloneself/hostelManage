<?php
namespace modules\platform\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Role;
use service\Pager;

class RoleController extends Controller{
    /**
     * 获取所有的角色
     * @return mixed
     */
    public function actionList(){
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        list($count,$list)=Pager::instance(Role::find(),$pageSize)->get($page);
        $res=[];
        foreach ($list as $item){
            $res[]=[
                'id'=>intval($item['id']),
                'name'=>$item['name'],
                'status'=>intval($item['status']),
                'statusLabel'=>$item['status']==Role::STATUS_ENABLE?'启用':'停用',
                'mark'=>$item['mark'],
                'mchCan'=>$item['mch_can']==Role::MCH_CAN_YES?'是':'否'
            ];
        }
        return \Yii::$app->responseHelper->success([
            'totalCount'=>$count,
            'list'=>$res
        ])->response();
    }

    /**
     * 删除角色
     * @return mixed
     */
    public function actionDelete(){
        $roleId=\Yii::$app->requestHelper->post('roleId',0,'int');
        if($roleId<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $role=Role::findOne(['id'=>$roleId]);
        if($role && !$role->delete()){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_FAIL)->response();
        }else{
            return \Yii::$app->responseHelper->success()->response();
        }
    }

    /**
     * 修改状态
     * @return mixed
     */
    public function actionChangeStatus(){
        $roleId=\Yii::$app->requestHelper->post('roleId',0,'int');
        if($roleId<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $role=Role::findOne(['id'=>$roleId]);
        if(empty($role)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $role->status=$role->status==Role::STATUS_ENABLE?Role::STATUS_DISABLE:Role::STATUS_ENABLE;
        if($role->update(false)){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL)->response();
        }
    }

    /**
     * 角色详情
     * @return mixed
     */
    public function actionDetail(){
        $roleId=\Yii::$app->requestHelper->post('id',0,'int');
        if($roleId<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $role=Role::findOne(['id'=>$roleId]);
        if(empty($role)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $res=[
            'id'=>intval($role->id),
            'name'=>$role->name,
            'code'=>$role->code,
            'mchCan'=>$role->mch_can==Role::MCH_CAN_YES?Role::MCH_CAN_YES:Role::MCH_CAN_NO,
            'status'=>intval($role->status),
            'mark'=>$role->mark
        ];
        return \Yii::$app->responseHelper->success($res)->response();
    }

    /**
     * 编辑、新增角色
     * @return mixed
     */
    public function actionEdit(){
        $roleId=\Yii::$app->requestHelper->post('id',0,'int');
        $name=\Yii::$app->requestHelper->post('name');
        $code=\Yii::$app->requestHelper->post('code');
        $mchCan=\Yii::$app->requestHelper->post('mchCan',0,'int');
        $mark=\Yii::$app->requestHelper->post('mark');
        if(empty($name) || empty($code)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }else if($roleId>0){
            $role=Role::findOne(['id'=>$roleId]);
            if(empty($role)){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
            }else if($role->code!=$code){
                $model=Role::findOne(['code'=>$code]);
                if($model){
                    return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
                }
            }
        }else{
            $model=Role::findOne(['code'=>$code]);
            if($model){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
            }
            $role=new Role();
        }
        $role->name=$name;
        $role->code=$code;
        $role->mch_can=$mchCan;
        $role->mark=$mark;
        $role->status=Role::STATUS_ENABLE;
        if($role->save()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_OPERATE_FAIL)->response();
        }
    }
}