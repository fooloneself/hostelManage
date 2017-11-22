<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Room;
use common\models\RoomDayPrice;
use common\models\RoomType;
use common\models\RoomWeekPrice;
use service\Pager;

class RoomPriceController extends Controller{

    /**
     * 设置房间周价格
     * @return mixed
     */
    public function actionRecord(){
        $monday=\Yii::$app->requestHelper->post('monday');
        $tuesday=\Yii::$app->requestHelper->post('tuesday');
        $wensday=\Yii::$app->requestHelper->post('wensday');
        $thursday=\Yii::$app->requestHelper->post('thursday');
        $friday=\Yii::$app->requestHelper->post('friday');
        $saturday=\Yii::$app->requestHelper->post('saturday');
        $sunday=\Yii::$app->requestHelper->post('sunday');
        $typeId=\Yii::$app->requestHelper->post('typeId');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $roomType=RoomType::findOne(['id'=>$typeId,'mch_id'=>$mchId]);
        if(empty($roomType)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_NOT_PRIVILEGE)->response();
        }
        $model=RoomWeekPrice::findOne(['type_id'=>$typeId,'mch_id'=>$mchId]);
        if(empty($model)){
            $model=new RoomWeekPrice();
            $model->type_id=$typeId;
            $model->mch_id=$mchId;
        }
        $model->monday=$monday;
        $model->tuesday=$tuesday;
        $model->wensday=$wensday;
        $model->thursday=$thursday;
        $model->friday=$friday;
        $model->saturday=$saturday;
        $model->sunday=$sunday;
        if($model->save()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_OPERATE_FAIL)->response();
        }
    }


    /**
     * 查看房间周价格
     * @return mixed
     */
    public function actionViewWeek(){
        $typeId=\Yii::$app->requestHelper->post('typeId',0,'int');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        if($typeId<=0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_UN_FIND)->response();
        }
        $price=RoomWeekPrice::find()->where(['type_id'=>$typeId,'mch_id'=>$mchId])->asArray()->one();
        return \Yii::$app->responseHelper->success($price)->response();
    }

    /**
     * 设置房间日价格
     * @return mixed
     */
    public function actionDayPriceSet(){
        $typeId=\Yii::$app->requestHelper->post('typeId');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $roomType=RoomType::findOne(['id'=>$typeId,'mch_id'=>$mchId]);
        $start=\Yii::$app->requestHelper->post('startDate',0,'int');
        $end=\Yii::$app->requestHelper->post('endDate',0,'int');
        $price=\Yii::$app->requestHelper->post('price',0,'float');
        $mark=\Yii::$app->requestHelper->post('mark','');
        if(empty($roomType) || empty($start) || empty($end) || $end<$start|| $price<0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $start=intval(date('Ymd',$start));
        $end=intval(date('Ymd',$end));
        if($start<intval(date('Ymd')) ){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        $query=RoomDayPrice::find()
            ->where(['or',':start between start_date and end_date',':end between start_date and end_date',':start<=start_date and :end>=end_date'],[
                ':start'=>$start,
                ':end'=>$end
            ]);
        if($id>0){
            $dayPrice=RoomDayPrice::findOne(['type_id'=>$typeId,'mch_id'=>$mchId,'id'=>$id]);
            if(empty($dayPrice)){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
            }
            $query->andWhere('id<>:id',[':id'=>$id]);
        }
        if($query->one()){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        if(empty($dayPrice)){
            $dayPrice=new RoomDayPrice();
            $dayPrice->type_id=$roomType->id;
            $dayPrice->mch_id=$mchId;
        }
        $dayPrice->start_date=$start;
        $dayPrice->end_date=$end;
        $dayPrice->price=$price;
        $dayPrice->mark=$mark;
        if($dayPrice->save()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_OPERATE_FAIL)->response();
        }
    }

    /**
     * 日价格列表
     * @return mixed
     */
    public function actionDayPrices(){
        $typeId=\Yii::$app->requestHelper->post('typeId');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $roomType=RoomType::findOne(['id'=>$typeId,'mch_id'=>$mchId]);
        if(empty($roomType)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $searchDate=\Yii::$app->requestHelper->post('searchDate',1,'int');
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $query=RoomDayPrice::find()
            ->where(['type_id'=>$typeId,'mch_id'=>$mchId]);
        if($searchDate>0){
            $query->andWhere(':search between start_date and end_date',[':search'=>intval(date('Ymd',$searchDate))]);
        }
        list($count,$list)=Pager::instance($query,$pageSize)->get($page);
        $res=[];
        foreach ($list as $item){
            $res[]=[
                'id'=>$item['id'],
                'mark'=>$item['mark'],
                'price'=>$item['price'],
                'during'=>date('Y-m-d',strtotime($item['start_date'])).'至'.date('Y-m-d',strtotime($item['end_date']))
            ];
        }
        return \Yii::$app->responseHelper->success([
            'totalCount'=>$count,
            'list'=>$res
        ])->response();
    }

    /**
     * 日价格
     * @return mixed
     */
    public function actionDayPrice(){
        $id=\Yii::$app->requestHelper->post('id');
        $typeId=\Yii::$app->requestHelper->post('typeId');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $dayPrice=RoomDayPrice::find()->where(['id'=>$id,'mch_id'=>$mchId,'type_id'=>$typeId])->one();
        if(empty($dayPrice)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        return \Yii::$app->responseHelper->success([
            'id'=>intval($dayPrice['id']),
            'startDate'=>date('Y-m-d',strtotime($dayPrice['start_date'])),
            'endDate'=>date('Y-m-d',strtotime($dayPrice['end_date'])),
            'price'=>floatval($dayPrice['price']),
            'mark'=>$dayPrice['mark']
        ])->response();
    }

    /**
     * 删除日价格
     * @return mixed
     */
    public function actionDelDayPrice(){
        $id=\Yii::$app->requestHelper->post('id');
        $typeId=\Yii::$app->requestHelper->post('typeId');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $dayPrice=RoomDayPrice::findOne(['id'=>$id,'mch_id'=>$mchId,'type_id'=>$typeId]);
        if($dayPrice && !$dayPrice->delete()){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_FAIL)->response();
        }
        return \Yii::$app->responseHelper->success()->response();
    }
}