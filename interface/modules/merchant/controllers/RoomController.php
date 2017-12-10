<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\library\Helper;
use common\models\Dictionary;
use common\models\DictionaryItem;
use common\models\Premises;
use common\models\Room;
use common\models\RoomServer;
use common\models\RoomType;
use service\Pager;

class RoomController extends Controller{

    /**
     * 录入房间信息
     * @return mixed
     */
    public function actionRecord(){
        $premises=\Yii::$app->user->getAdmin()->getMerchant()->getPremise()->id;
        $number=\Yii::$app->requestHelper->post('number',0,'int');
        $bedNumber=\Yii::$app->requestHelper->post('bedNumber',1,'int');
        $type=\Yii::$app->requestHelper->post('type',0,'int');
        $introduce=\Yii::$app->requestHelper->post('introduce','','string');
        $close=\Yii::$app->requestHelper->post('lock',0,'int');
        $servers=\Yii::$app->requestHelper->post('servers');
        $roomId=\Yii::$app->requestHelper->post('id',0,'int');
        if($bedNumber<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'床位数错误')->response();
        }else if($number<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'房间编号错误')->response();
        }
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $model=RoomType::findOne(['id'=>$type,'mch_id'=>$mchId]);
        if(empty($model)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'类型未找到')->response();
        }
        $status=$close==1?Room::STATUS_UN_OPEN:Room::STATUS_CAN_ORDER;
        if($roomId>0){
            return $this->update($roomId,$mchId,$premises,$number,$type,$bedNumber,$status,$introduce,$servers);
        }else{
            return $this->add($mchId,$premises,$number,$type,$bedNumber,$status,$introduce,$servers);
        }
    }

    /**
     * 新增房间
     * @param $mchId
     * @param $premises
     * @param $number
     * @param $type
     * @param $bedNumber
     * @param $status
     * @param $introduce
     * @param $servers
     * @return mixed
     */
    protected function add($mchId,$premises,$number,$type,$bedNumber,$status,$introduce,$servers){
        $model=Room::findOne(['number'=>$number,'premises_id'=>$premises]);
        if($model){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'房间'.$number.'已存在')->response();
        }
        $model=new Room();
        $model->setAttributes([
            'premises_id'=>$premises,
            'number'=>$number,
            'create_time'=>time(),
            'mch_id'=>$mchId,
            'bed_num'=>$bedNumber,
            'status'=>$status,
            'type'=>$type,
            'introduce'=>$introduce
        ]);
        if($model->insert(false)){
            $this->addServiceToRoom($model->id,$servers);
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
     * @param $number
     * @param $type
     * @param $bedNumber
     * @param $status
     * @param $introduce
     * @param $servers
     * @return mixed
     */
    protected function update($roomId,$mchId,$premisesId,$number,$type,$bedNumber,$status,$introduce,$servers){
        $room=Room::findOne(['mch_id'=>$mchId,'id'=>$roomId]);
        if(empty($room)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'房间不存在')->response();
        }
        if($room->number!=$number || $room->premises_id!=$premisesId){
            $model=Room::findOne(['premises_id'=>$premisesId,'number'=>$number]);
            if(!empty($model)){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'房间'.$number.'已存在')->response();
            }
        }
        unset($model);
        $room->setAttributes([
            'premises_id'=>$premisesId,
            'number'=>$number,
            'mch_id'=>$mchId,
            'bed_num'=>$bedNumber,
            'status'=>$status,
            'type'=>$type,
            'introduce'=>$introduce
        ]);
        if($room->save(false)){
            $this->addServiceToRoom($room->id,$servers);
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL,'房间信息修改失败')->response();
        }
    }

    /**
     * 批量插入
     * @param $roomId
     * @param $servers
     * @return bool
     */
    protected function addServiceToRoom($roomId,$servers){
        RoomServer::deleteAll(['room_id'=>$roomId]);
        if(empty($servers))return true;
        $data=[];
        foreach ($servers as $server){
            $data[]=[$roomId,$server];
        }
        $res=\Yii::$app->db->createCommand()->batchInsert(RoomServer::tableName(),['room_id','dictionary_key'],$data)->execute();
    }
    /**
     * 删除房间信息
     * @return mixed
     */
    public function actionDelete(){
        $room=\Yii::$app->requestHelper->post('id',0,'int');
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
    public function actionList(){
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $query=Room::find()
            ->select('r.*,rt.name as type_name,group_concat(di.value) as server_name')
            ->alias('r')
            ->leftJoin(RoomType::tableName().' rt','r.type=rt.id')
            ->leftJoin(RoomServer::tableName().' rs','r.id=rs.room_id')
            ->leftJoin(DictionaryItem::tableName().' di','rs.dictionary_key=di.key and di.code=:code',[':code'=>Dictionary::DICTIONARY_ROOM_SERVER])
            ->where(['r.mch_id'=>$mchId])
            ->groupBy('r.id')
            ->orderBy('number asc');
        list($count,$rooms)=Pager::instance($query,$pageSize)->get($page);
        $res=[];
        if(!empty($rooms)){
            foreach ($rooms as $room){
                $res[]=[
                    'id'=>$room['id'],
                    'typeName'=>$room['type_name'],
                    //'defaultPrice'=>$room['default_price'],
                    'number'=>$room['number'],
                    'isLock'=>$room['status']==2?'是':'否',
                    'serverName'=>$room['server_name']
                ];
            }
        }
        return \Yii::$app->responseHelper->success([
            'totalCount'=>$count,
            'list'=>$res
        ])->response();
    }

    /**
     * 房间编辑页信息
     * @return mixed
     */
    public function actionEditPageInfo(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $roomId=\Yii::$app->requestHelper->post('id',0,'int');
        $room=null;
        if($roomId>0){
            $res=Room::find()->where(['id'=>$roomId])->asArray()->one();
            $room=[];
            $room['id']=intval($res['id']);
            $room['type']=$res['type'];
            $room['lock']=$res['status']==2?1:0;
            $room['number']=$res['number'];
            $room['introduce']=$res['introduce'];
            $room['servers']=RoomServer::find()
                ->select('dictionary_key')
                ->where(['room_id'=>$roomId])
                ->column();
        }
        $types=RoomType::find()
            ->select('id,name')
            ->where(['mch_id'=>$mchId])
            ->asArray()->all();
        $servers=DictionaryItem::find()
            ->select('key,value')
            ->where(['code'=>Dictionary::DICTIONARY_ROOM_SERVER])
            ->asArray()->all();
        return \Yii::$app->responseHelper->success([
            'room'=>$room,
            'types'=>$types,
            'servers'=>$servers,
        ])->response();
    }

    /**
     * 锁房
     * @return mixed
     */
    public function actionLock(){
        $roomId=\Yii::$app->requestHelper->post("id",0,'int');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        if($roomId<=0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $model=Room::findOne(['mch_id'=>$mchId,'id'=>$roomId]);
        if(empty($model)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $model->status=Room::STATUS_UN_OPEN;
        if($model->update(false)){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL)->response();
        }
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

    /**
     * 下单页面信息
     * @return mixed
     */
    public function actionPlacePage(){
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        $merchant=\Yii::$app->user->getAdmin()->getMerchant();
        $room=Room::find()->alias('r')
            ->select('r.*,rt.name as type_name,rt.allow_hour_room,rt.default_price')
            ->leftJoin(RoomType::tableName().' rt','r.type=rt.id')
            ->where(['r.premises_id'=>$merchant->getPremise()->id,'r.mch_id'=>$merchant->getId(),'r.id'=>$id])
            ->asArray()->one();
        return \Yii::$app->responseHelper->success([
            'room'=>$room,
            'date'=>date('Y/m/d',time())
        ])->response();
    }

    /**
     * 获取所有房间
     * @return mixed
     */
    public function actionAllRoom(){
        $mch=\Yii::$app->user->getAdmin()->getMerchant();
        $mchId=$mch->getId();
        $premisesId=$mch->getPremise()->id;
        $rooms=Room::find()
            ->select('id,number')
            ->where(['mch_id'=>$mchId,'premises_id'=>$premisesId])
            ->orderBy('number asc')
            ->asArray()->all();
        return \Yii::$app->responseHelper->success($rooms)->response();
    }

    public function actionConsumptionBill(){
        $merchant=\Yii::$app->user->getAdmin()->getMerchant();
        $roomId=\Yii::$app->requestHelper->post('roomId',0,'int');
        $num=\Yii::$app->requestHelper->post('num',1,'int');
        $room=\service\order\Room::byId($merchant,$roomId);
        if(empty($room)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $start=$_SERVER['REQUEST_TIME'];
        $end=strtotime(date('Y-m-d',$start+86400*$num).' '.$merchant->getSetting()->check_out_time);
        $bill=$room->generateDaysBill($start,$end,-1);
        $billList=$bill->getList();
        $res=[];
        foreach ($billList as $billItem){
            $res[]=[
                'date'=>implode('/',[$billItem->year,$billItem->month,$billItem->day]),
                'number'=>$room->getNumber(),
                'amount'=>$billItem->amount,
                'typeName'=>$room->getTypeName()
            ];
        }
        return \Yii::$app->responseHelper->success([
            'list'=>$res,
            'totalAmount'=>$bill->getTotalAmount()
        ])->response();
    }
}