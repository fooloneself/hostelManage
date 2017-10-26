<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Notice;
use common\models\NoticeRead;
use service\notice\MchNoticeList;

class NoticeController extends Controller{

    /**
     * 公告列表
     * @return mixed
     */
    public function actionList(){
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $adminId=\Yii::$app->user->getAdminId();
        $list=new MchNoticeList();
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
     * 阅读公告
     * @return mixed
     */
    public function actionRead(){
        $adminId=\Yii::$app->user->getAdminId();
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        if($id<=0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $notice=Notice::find()
            ->select('title,content,id,public_time')
            ->where(['id'=>$id,'status'=>Notice::STATUS_PUBLIC])
            ->asArray()
            ->one();
        $read=NoticeRead::findOne(['admin_id'=>$adminId,'notice_id'=>$id]);
        if(empty($read)){
            $read=new NoticeRead();
            $read->admin_id=$adminId;
            $read->notice_id=$id;
            $read->read_time=time();
            $read->insert(false);
        }
        $notice['publicDate']=date('Y-m-d H:i:s',$notice['public_time']);
        unset($notice['public_time']);
        return \Yii::$app->responseHelper->success($notice)->response();
    }
}