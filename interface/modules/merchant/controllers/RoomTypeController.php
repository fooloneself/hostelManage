<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\RoomType;

class RoomTypeController extends Controller{

    /**
     * 新增、修改房间类型
     * @return mixed
     */
    public function actionRecord(){
        $name=\Yii::$app->requestHelper->post('name','','string');
        $defaultPrice=\Yii::$app->requestHelper->post('defaultPrice',0,'float');
        $allowHourRoom=\Yii::$app->requestHelper->post('allowHourRoom',0,'int');
        $hourRoomPrice=\Yii::$app->requestHelper->post('hourRoomPrice',0,'float');
        $introduce=\Yii::$app->requestHelper->post('introduce','','string');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $id=\Yii::$app->requestHelper->post('id');
        if($id>0){
            $roomType=RoomType::findOne(['id'=>$id,'mch_id'=>$mchId]);
            if(empty($roomType)){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_NOT_EXISTS,'类型不存在')->response();
            }
            $roomType->name=$name;
            $roomType->default_price=$defaultPrice;
            $roomType->allow_hour_room=$allowHourRoom;
            $roomType->hour_room_price=$hourRoomPrice;
            $roomType->introduce=$introduce;
            if($roomType->update(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL)->response();
            }
        }else{
            $roomType=new RoomType();
            $roomType->mch_id=$mchId;
            $roomType->name=$name;
            $roomType->default_price=$defaultPrice;
            $roomType->allow_hour_room=$allowHourRoom;
            $roomType->hour_room_price=$hourRoomPrice;
            $roomType->introduce=$introduce;
            if($roomType->insert(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
            }
        }
    }

    /**
     * 单个信息
     * @return mixed
     */
    public function actionView(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $id=\Yii::$app->requestHelper->post('id');
        $roomType=RoomType::findOne(['id'=>$id,'mch_id'=>$mchId]);
        if(empty($roomType)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_NOT_EXISTS)->response();
        }else{
            return \Yii::$app->responseHelper->success($roomType->getAttributes())->response();
        }
    }

    public function actionList(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $query=RoomType::find()->where(['mch_id'=>$mchId]);
        $count=$query->count();
        $list=$query->offset(($page-1)*$pageSize)->limit($pageSize)->asArray()->all();
        return \Yii::$app->responseHelper->success([
            'total'=>$count,
            'list'=>$list
        ])->response();
    }
}