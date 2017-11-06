<?php
namespace modules\platform\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Admin;
use common\models\AdminInfo;
use common\models\Feedback;
use service\Pager;

class FeedbackController extends Controller{

    /**
     * 反馈列表
     * @return mixed
     */
    public function actionList(){
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $status=\Yii::$app->requestHelper->post('status',-1,'int');
        $query=Feedback::find()->alias('f')
            ->select('a.user_name,ai.name,f.*')
            ->leftJoin(Admin::tableName().' a','a.id=f.feedback_admin_id')
            ->leftJoin(AdminInfo::tableName().' ai','f.feedback_admin_id=ai.admin_id');
        if($status==Feedback::STATUS_HANDLED || $status==Feedback::STATUS_FAIL || $status==Feedback::STATUS_HANDLING){
            $query->where(['f.status'=>$status]);
        }
        list($count,$list)=Pager::instance($query,$pageSize)->get($page);
        $adminId=\Yii::$app->user->getAdminId();
        $l=[];
        foreach ($list as $item){
            $l[]=[
                'id'=>intval($item['id']),
                'name'=>$item['name']?:$item['user_name'],
                'date'=>date('Y/m/d ',$item['create_time']),
                'content'=>$item['content'],
                'answer'=>$item['answer'],
                'hasAnswer'=>$item['status']!=Feedback::STATUS_HANDLING,
                'canCancel'=>($item['status']==Feedback::STATUS_HANDLING || $item['answer_admin_id']==$adminId)?true:false
            ];
        }
        return \Yii::$app->responseHelper->success([
            'totalCount'=>$count,
            'list'=>$l
        ])->response();
    }

    /**
     * 回复
     * @return mixed
     */
    public function actionAnswer(){
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        $answer=\Yii::$app->requestHelper->post('answer','','string');
        if($id<1 || empty($answer)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $feedback=Feedback::findOne(['id'=>$id]);
        if(empty($feedback)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $feedback->answer_admin_id=\Yii::$app->user->getAdminId();
        $feedback->answer=$answer;
        $feedback->answer_time=$_SERVER['REQUEST_TIME'];
        $feedback->status=Feedback::STATUS_HANDLED;
        $feedback->update(false);
        return \Yii::$app->responseHelper->success()->response();
    }

    /**
     * 取消回复
     * @return mixed
     */
    public function actionCancelAnswer(){
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        if($id<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $adminId=\Yii::$app->user->getAdminId();
        $feedback=Feedback::findOne(['id'=>$id]);
        if(empty($feedback)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }else if($feedback->answer_admin_id!=$adminId){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_CANCEL_NO_RIGHT)->response();
        }
        $feedback->answer_admin_id=0;
        $feedback->answer='';
        $feedback->answer_time=0;
        $feedback->status=Feedback::STATUS_HANDLING;
        $feedback->update(false);
        return \Yii::$app->responseHelper->success()->response();
    }
}