<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\library\Helper;
use common\models\Admin;
use common\models\AdminInfo;
use common\models\AdminRole;
use common\models\Role;
use service\Pager;

class AdminController extends Controller{

    /**
     * 重置登录密码
     * @return mixed
     */
    public function actionResetPassword(){
        $oldPwd=\Yii::$app->requestHelper->post('oldPassword','','string');
        $newPwd=\Yii::$app->requestHelper->post('newPassword','','string');
        $confirmPwd=\Yii::$app->requestHelper->post('confirmPassword','','string');
        $adminId=\Yii::$app->requestHelper->post('adminId',0,'int');
        if($adminId>0){
            $admin=\service\admin\Admin::byId($adminId);
            if(!$admin->isExists()){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
            }else if($admin->getMchId()!=\Yii::$app->user->getAdmin()->getMchId()){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
            }
        }else{
            $admin=\Yii::$app->user->getAdmin();
        }
        if(empty($oldPwd) || empty($newPwd) || empty($confirmPwd)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }else if($newPwd!=$confirmPwd){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'两次密码输入不一致')->response();
        }else if(!$admin->isEqualToPwd($oldPwd)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PWD_ERROR,'旧密码输入错误')->response();
        }else if($admin->resetPwd($newPwd)){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL,'密码重置失败')->response();
        }
    }

    /**
     * 账号补充信息
     * @return mixed
     */
    public function actionInfoModify(){
        $name=\Yii::$app->requestHelper->post('name','','string');
        $mobile=\Yii::$app->requestHelper->post('mobile','','string');
        $sex=\Yii::$app->requestHelper->post('sex',0,'int');
        $birthday=\Yii::$app->requestHelper->post('birthday',0,'int');
        if(empty($name) || empty($mobile)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        $adminId=\Yii::$app->user->getAdminId();
        $model=AdminInfo::findOne(['admin_id'=>$adminId]);
        if(empty($model)){
            $model=new AdminInfo();
            $model->admin_id=$adminId;
        }
        $model->setAttributes([
            'name'=>$name,
            'mobile'=>$mobile,
            'sex'=>$sex,
            'birthday'=>intval($birthday)
        ]);
        if($model->save()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL)->response();
        }
    }

    /**
     * 查看个人信息
     * @return mixed
     */
    public function actionInfo(){
        $admin=AdminInfo::find()->where(['admin_id'=>\Yii::$app->user->getAdminId()])->asArray()->one();
        if($admin){
            $admin['birthday']=$admin['birthday']>0?date('Y-m-d',$admin['birthday']):'';
        }
        return \Yii::$app->responseHelper->success($admin)->response();
    }

    /**
     * 商户管理账号列表
     * @return mixed
     */
    public function actionList(){
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $query=Admin::find()->alias('a')
            ->select('a.id,a.user_name,ai.name,r.name as role_name,a.status')
            ->leftJoin(AdminInfo::tableName().' ai','a.id=ai.admin_id')
            ->leftJoin(AdminRole::tableName().' ar','ar.admin_id=a.id')
            ->leftJoin(Role::tableName().' r','r.id=ar.role_id and r.status=:status and r.mch_can=:mchCan',[
                ':status'=>Role::STATUS_ENABLE,
                ':mchCan'=>Role::MCH_CAN_YES
            ])
            ->where(['a.mch_id'=>$mchId]);
        list($count,$list)=Pager::instance($query,$pageSize)->get($page);
        $res=[];
        foreach ($list as $item){
            $status=intval($item['status']);
            $res[]=[
                'id'=>intval($item['id']),
                'userName'=>strval($item['user_name']),
                'name'=>strval($item['name']),
                'roleName'=>strval($item['role_name']),
                'statusLabel'=>$status==Admin::STATUS_ENABLE ? '启用':'停用',
                'status'=>$status
            ];
        }
        return \Yii::$app->responseHelper->success([
            'totalCount'=>$count,
            'list'=>$res
        ])->response();
    }

    /**
     * 删除账号
     * @return mixed
     */
    public function actionDelete(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $adminId=\Yii::$app->requestHelper->post('adminId',0,'int');
        if($adminId<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $admin=Admin::findOne(['id'=>$adminId,'mch_id'=>$mchId,'is_super'=>Admin::SUPER_NO]);
        if(empty($admin)){
            return \Yii::$app->responseHelper->success()->response();
        }
        $transaction=\Yii::$app->db->beginTransaction();
        if(!$admin->delete()){
            $transaction->rollBack();
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_FAIL)->response();
        }
        $admin=AdminInfo::findOne(['admin_id'=>$adminId]);
        if($admin && !$admin->delete()){
            $transaction->rollBack();
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_FAIL)->response();
        }
        AdminRole::deleteAll(['admin_id'=>$adminId]);
        $transaction->commit();
        return \Yii::$app->responseHelper->success()->response();
    }

    /**
     * 修改状态
     * @return mixed
     */
    public function actionChange(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $adminId=\Yii::$app->requestHelper->post('adminId',0,'int');
        if($adminId<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $admin=Admin::findOne(['id'=>$adminId,'mch_id'=>$mchId]);
        if(empty($admin)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $admin->status=$admin->status==Admin::STATUS_ENABLE?Admin::STATUS_DISABLE:Admin::STATUS_ENABLE;
        if($admin->update(false)){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL)->response();
        }
    }

    /**
     * 新增账号
     * @return mixed
     */
    public function actionAdd(){
        $userName=\Yii::$app->requestHelper->post('username','','string');
        $password=\Yii::$app->requestHelper->post('password','','string');
        $name=\Yii::$app->requestHelper->post('name','','string');
        $mobile=\Yii::$app->requestHelper->post('mobile','','string');
        $sex=\Yii::$app->requestHelper->post('sex',0,'int');
        $birthday=\Yii::$app->requestHelper->post('birthday',0,'int');
        $roleId=\Yii::$app->requestHelper->post('roleId',0,'int');
        if(empty($name) || empty($mobile) || empty($userName) || empty($password)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        if(!Helper::checkPwd($password)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PWD_FORMAT)->response();
        }
        $admin=Admin::findOne(['user_name'=>$userName]);
        if($admin){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_USER_EXISTS)->response();
        }
        $transaction=\Yii::$app->db->beginTransaction();
        $admin=new Admin();
        $admin->user_name=$userName;
        $admin->password=Helper::encryptPwd($password);
        $admin->role_id=$roleId;
        $admin->mch_id=\Yii::$app->user->getAdmin()->getMchId();
        $admin->is_super=Admin::SUPER_NO;
        $admin->status=Admin::STATUS_ENABLE;
        if(!$admin->insert()){
            $transaction->rollBack();
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL,'账号添加失败')->response();
        }
        if($roleId>0){
            $model=new AdminRole();
            $model->admin_id=$admin->id;
            $model->role_id=$roleId;
            $model->insert(false);
        }
        $model=new AdminInfo();
        $model->setAttributes([
            'name'=>$name,
            'mobile'=>$mobile,
            'sex'=>$sex,
            'birthday'=>$birthday,
            'admin_id'=>$admin->id
        ]);
        if($model->insert()){
            $transaction->commit();
            return \Yii::$app->responseHelper->success()->response();
        }else{
            $transaction->rollBack();
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
        }
    }

    /**
     * 获取所有的角色
     * @return mixed
     */
    public function actionRoles(){
        $roles=Role::find()->where(['status'=>Role::STATUS_ENABLE,'mch_can'=>Role::MCH_CAN_YES])->asArray()->all();
        return \Yii::$app->responseHelper->success($roles)->response();
    }
}