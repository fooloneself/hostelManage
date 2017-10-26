<?php
namespace modules\platform\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Notice;
use common\models\NoticeRead;
use service\notice\PlatformNoticeList;

class NoticeController extends Controller{

    /**
     * 列表
     * @return mixed
     */
    public function actionList(){
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $adminId=\Yii::$app->user->getAdminId();
        $list=new PlatformNoticeList();
        $list->adminId=$adminId;
        $list->pageSize=$pageSize;
        $list->page=$page;
        list($count,$list)=$list->get();
        return \Yii::$app->responseHelper->success([
            'totalCount'=>$count,
            'list'=>$list
        ])->response();
    }

    /**
     * 查看公告详情
     * @return mixed
     */
    public function actionView(){
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        $adminId=\Yii::$app->user->getAdminId();
        if($id<=0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $notice=Notice::find()
            ->select('id,title,content,status')
            ->where(['admin_id'=>$adminId,'id'=>$id])
            ->asArray()->one();
        return \Yii::$app->responseHelper->success($notice)->response();
    }

    /**
     * 修改、新增公告
     * @return mixed
     */
    public function actionEdit(){
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        $status=\Yii::$app->requestHelper->post('status',0,'int');
        $title=\Yii::$app->requestHelper->post('title','','int');
        $content=\Yii::$app->requestHelper->post('content','','int');
        $adminId=\Yii::$app->user->getAdminId();
        if(empty($title) || empty($content)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }else if($status!=Notice::STATUS_PUBLIC && $status!=Notice::STATUS_DRAFT){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        if($id>0){
            $notice=Notice::findOne(['id'=>$id,'admin_id'=>$adminId]);
            if(empty($notice)){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
            }else if($notice->status==Notice::STATUS_PUBLIC){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_OPERATE_FAIL,'已发布不能修改')->response();
            }
            $notice->title=$title;
            $notice->content=$content;
            $notice->status=$status;
            if($status==Notice::STATUS_PUBLIC){
                $notice->public_time=time();
            }
            if($notice->update()){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_OPERATE_FAIL)->response();
            }
        }else{
            $notice=new Notice();
            $notice->title=$title;
            $notice->content=$content;
            $notice->admin_id=$adminId;
            $notice->status=$status;
            $notice->create_time=time();
            if($status=Notice::STATUS_PUBLIC){
                $notice->public_time=time();
            }
            if($notice->insert()){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
            }
        }
    }

    /**
     * 撤回
     * @return mixed
     */
    public function actionRevoke(){
        $adminId=\Yii::$app->user->getAdminId();
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        if($id<=0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $notice=Notice::findOne(['id'=>$id,'admin_id'=>$adminId]);
        if(empty($notice)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'未找到公告')->response();
        }
        NoticeRead::deleteAll(['notice_id'=>$id]);
        $notice->status=Notice::STATUS_REVOKE;
        $notice->revoke_time=time();
        if($notice->update(false)){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL,'撤销失败')->response();
        }
    }

    /**
     * 发布公告
     * @return mixed
     */
    public function actionPublic(){
        $adminId=\Yii::$app->user->getAdminId();
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        if($id<=0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $notice=Notice::findOne(['id'=>$id,'admin_id'=>$adminId]);
        if(empty($notice)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'未找到公告')->response();
        }
        $notice->status=Notice::STATUS_PUBLIC;
        $notice->public_time=time();
        if($notice->update(false)){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL,'发布失败')->response();
        }
    }

    /**
     * 删除公告
     * @return mixed
     */
    public function actionDelete(){
        $adminId=\Yii::$app->user->getAdminId();
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        if($id<=0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $notice=Notice::findOne(['id'=>$id,'admin_id'=>$adminId]);
        if($notice && !$notice->delete()){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL,'删除失败')->response();
        }else{
            return \Yii::$app->responseHelper->success()->response();
        }
    }
}