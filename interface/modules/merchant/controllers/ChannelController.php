<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Channel;

class ChannelController extends Controller{

    /**
     * 渠道新增、修改
     * @return mixed
     */
    public function actionRecord(){
        $label=\Yii::$app->requestHelper->post('label');
        $commission=\Yii::$app->requestHelper->post('commission');
        $introduce=\Yii::$app->requestHelper->post('introduce','');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $id=\Yii::$app->requestHelper->post('id');
        if($id>0){
            $channel=Channel::findOne(['id'=>$id,'mch_id'=>$mchId]);
            if(empty($channel)){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_CHANNEL_NOT_EXISTS)->response();
            }
            $channel->setAttributes(['commission'=>$commission,'name'=>$label,'introduce'=>$introduce]);
            if($channel->save(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL)->response();
            }
        }else{
            $channel=new Channel();
            $channel->setAttributes(['commission'=>$commission,'name'=>$label,'introduce'=>$introduce,'mch_id'=>$mchId]);
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
    public function actionView(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $list=Channel::find()->where(['mch_id'=>$mchId])->asArray()->all();
        return \Yii::$app->responseHelper->success($list)->response();
    }
}