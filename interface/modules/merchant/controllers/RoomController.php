<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\library\Helper;
use common\models\Premises;
use common\models\Room;
use common\models\RoomType;

class RoomController extends Controller{

    /**
     * 录入房间信息
     * @return mixed
     */
    public function actionRecord(){
        $premises=\Yii::$app->requestHelper->post('premises',0,'int');
        $number=\Yii::$app->requestHelper->post('number',0,'int');
        $bedNumber=\Yii::$app->requestHelper->post('bedNumber',1,'int');
        $floor=\Yii::$app->requestHelper->post('floor',0,'int');
        $type=\Yii::$app->requestHelper->post('type',0,'int');
        $introduce=\Yii::$app->requestHelper->post('introduce','','string');
        $close=\Yii::$app->requestHelper->post('close',0,'int');
        $roomId=\Yii::$app->requestHelper->post('roomId',0,'int');
        if($bedNumber<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'床位数错误')->response();
        }else if($number<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'房间编号错误')->response();
        }else if($floor<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'楼层数错误')->response();
        }
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $model=RoomType::findOne(['id'=>$type,'mch_id'=>$mchId]);
        if(empty($model)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'类型未找到')->response();
        }
        $status=$close===1?Room::STATUS_UN_OPEN:Room::STATUS_CAN_ORDER;
        if($roomId>0){
            return $this->update($roomId,$mchId,$premises,$floor,$number,$type,$bedNumber,$status,$introduce);
        }else{
            return $this->add($mchId,$premises,$floor,$number,$type,$bedNumber,$status,$introduce);
        }
    }

    /**
     * 新增房间
     * @param $mchId
     * @param $premises
     * @param $floor
     * @param $number
     * @param $type
     * @param $bedNumber
     * @param $status
     * @param $introduce
     * @return mixed
     */
    protected function add($mchId,$premises,$floor,$number,$type,$bedNumber,$status,$introduce){
        $model=Premises::findOne(['id'=>$premises,'mch_id'=>$mchId]);
        if(empty($model)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'未找到经营场所')->response();
        }
        $model=Room::findOne(['floor'=>$floor,'number'=>$number,'premises_id'=>$premises]);
        if($model){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'房间'.Helper::getRoomNo($floor,$number).'已存在')->response();
        }
        $model=new Room();
        $model->setAttributes([
            'premises_id'=>$premises,
            'number'=>$number,
            'create_time'=>time(),
            'mch_id'=>$mchId,
            'bed_num'=>$bedNumber,
            'floor'=>$floor,
            'status'=>$status,
            'type'=>$type,
            'introduce'=>$introduce
        ]);
        if($model->insert(false)){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL,'房间录入失败')->response();
        }
    }

    /**
     * 修改房间信息
     * @param $roomId
     * @param $mchId
     * @param $premisesId
     * @param $floor
     * @param $number
     * @param $type
     * @param $bedNumber
     * @param $status
     * @param $introduce
     * @return mixed
     */
    protected function update($roomId,$mchId,$premisesId,$floor,$number,$type,$bedNumber,$status,$introduce){
        $room=Room::findOne(['mch_id'=>$mchId,'id'=>$roomId]);
        if(empty($room)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'房间不存在')->response();
        }else if($room->premises_id!=$premisesId){
            $model=Premises::findOne(['id'=>$premisesId,'mch_id'=>$mchId]);
            if(empty($model)){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'未找到经营场所')->response();
            }
        }
        $model=Room::findOne(['premises_id'=>$premisesId,'floor'=>$floor,'number'=>$number]);
        if(!empty($model)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'房间'.Helper::getRoomNo($floor,$number).'已存在')->response();
        }
        unset($model);
        $room->setAttributes([
            'premises_id'=>$premisesId,
            'number'=>$number,
            'mch_id'=>$mchId,
            'bed_num'=>$bedNumber,
            'floor'=>$floor,
            'status'=>$status,
            'type'=>$type,
            'introduce'=>$introduce
        ]);
        if($room->save(false)){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL,'房间信息修改失败')->response();
        }
    }
    /**
     * 删除房间信息
     * @return mixed
     */
    public function actionDelete(){
        $room=\Yii::$app->requestHelper->post('room',0,'int');
        if($room<1)return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        $mchId=\Yii::$app->user->getAdmin()->getMerchant()->getId();
        $room=Room::findOne(['id'=>$room,'mch_id'=>$mchId]);
        if(empty($room)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'未找到要删除的房间')->response();
        }
        if($room->delete()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_FAIL,'房间信息删除失败')->response();
        }
    }

    /**
     * 房间列表
     * @return mixed
     */
    public function actionView(){
        $mchId=\Yii::$app->user->getAdmin()->getMerchant()->getId();
        $rooms=Room::find()
            ->where(['mch_id'=>$mchId])
            ->orderBy('floor asc,number asc')
            ->asArray()->all();
        $res=[];
        foreach ($rooms as $room){
            $res[]=[
                'number'=>Helper::getRoomNo($room['floor'],$room['number']),
                'status'=>intval($room['status'])
            ];
        }
        return \Yii::$app->responseHelper->success($res)->response();
    }

    /**
     * 上传房间图片
     * @return mixed
     */
    public function actionPicture(){
        $roomId=\Yii::$app->requestHelper->post('room',0,'int');
        $pic=\Yii::$app->requestHelper->post('pic','','string');
        $cover=\Yii::$app->requestHelper->post('cover','','string');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $room=Room::findOne(['mch_id'=>$mchId,'id'=>$roomId]);
        if($roomId<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }else if(empty($room)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'未找到房间')->response();
        }
        $room->setAttributes([
            'pic'=>$pic,
            'cover'=>$cover
        ]);
        if($room->save()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL,'图片修改失败')->response();
        }
    }
}