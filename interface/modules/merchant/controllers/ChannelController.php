<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Channel;
use service\Pager;

class ChannelController extends Controller{

    /**
     * 渠道新增、修改
     * @return mixed
     */
    public function actionRecord(){
        $name=\Yii::$app->requestHelper->post('name');
        $commission=\Yii::$app->requestHelper->post('commission');
        $introduce=\Yii::$app->requestHelper->post('introduce','');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $id=\Yii::$app->requestHelper->post('id');
        if($id>0){
            $channel=Channel::findOne(['id'=>$id,'mch_id'=>$mchId]);
            if(empty($channel)){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_CHANNEL_NOT_EXISTS)->response();
            }
            $channel->setAttributes(['commission'=>$commission,'name'=>$name,'introduce'=>$introduce]);
            if($channel->update(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL)->response();
            }
        }else{
            $channel=new Channel();
            $channel->setAttributes(['commission'=>$commission,'name'=>$name,'introduce'=>$introduce,'mch_id'=>$mchId]);
            if($channel->insert(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
            }
        }
    }

    /**
     * 删除渠道
     * @return mixed
     */
    public function actionDelete(){
        $id=\Yii::$app->requestHelper->post('id');
        if($id<=0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $channel=Channel::findOne(['id'=>$id]);
        if($channel && !$channel->delete()){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_FAIL)->response();
        }else{
            return \Yii::$app->responseHelper->success()->response();
        }
    }

    /**
     * 渠道列表
     * @return mixed
     */
    public function actionList(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $query=Channel::find()->where(['mch_id'=>$mchId]);
        list($totalCount,$list)=Pager::instance($query,$pageSize)->get($page);
        return \Yii::$app->responseHelper->success([
            'totalCount'=>$totalCount,
            'list'=>$list
        ])->response();
    }

    /**
     * 渠道信息
     * @return mixed
     */
    public function actionView(){
        $channelId=\Yii::$app->requestHelper->post("id",0,'int');
        if($channelId<=0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $channel=Channel::find()->where(['id'=>$channelId,'mch_id'=>$mchId])->asArray()->one();
        return \Yii::$app->responseHelper->success($channel)->response();
    }
}