<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\MerchantActiveDate;
use common\models\MerchantActivity;
use common\models\MerchantActivityCondition;
use common\models\MerchantMemberRankDivide;
use common\models\Room;
use common\models\RoomType;
use service\Pager;

class ActivityController extends Controller{

    /**
     * 折扣价列表
     * @return mixed
     */
    public function actionDiscountList(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $query=MerchantActivity::find()->alias('ma')
            ->select('ma.*,group_concat(mac.condition_identity) as rank_id,group_concat(mmrd.name) as rank_name')
            ->leftJoin(MerchantActivityCondition::tableName().' mac','ma.id=mac.active_id and mac.type=:type',[
                ':type'=>MerchantActivityCondition::TYPE_MEMBER_RANK
            ])
            ->leftJoin(MerchantMemberRankDivide::tableName().' mmrd','mac.condition_identity=mmrd.id')
            ->where(['ma.mch_id'=>$mchId,'ma.type'=>MerchantActivity::TYPE_DISCOUNT,'ma.status'=>MerchantActivity::STATUS_USABLE])
            ->groupBy('ma.id');
        list($count,$list)=Pager::instance($query,$pageSize)->get($page);
        $res=[];
        foreach ($list as $item){
            if($item['rank_id']==='' || $item['rank_id']===null){
                $memberRank='不限';
            }else if(in_array(0,explode(',',$item['rank_id']))){
                $memberRank=trim('非会员,'.$item['rank_name'],',');
            }else{
                $memberRank=$item['rank_name'];
            }
            $res[]=[
                'id'=>$item['id'],
                'name'=>$item['name'],
                'discount'=>$item['discount'],
                'memberRank'=>$memberRank
            ];
        }
        return \Yii::$app->responseHelper->success([
            'totalCount'=>$count,
            'list'=>$res
        ])->response();
    }

    /**
     * 特价列表
     * @return mixed
     */
    public function actionSpecialOfferList(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $query=MerchantActivity::find()->alias('ma')
            ->select('ma.*,group_concat(mac.condition_identity) as rank_id,group_concat(mmrd.name) as rank_name')
            ->leftJoin(MerchantActivityCondition::tableName().' mac','ma.id=mac.active_id and mac.type=:type',[
                ':type'=>MerchantActivityCondition::TYPE_MEMBER_RANK
            ])
            ->leftJoin(MerchantMemberRankDivide::tableName().' mmrd','mac.condition_identity=mmrd.id')
            ->where(['ma.mch_id'=>$mchId,'ma.type'=>MerchantActivity::TYPE_SPECIAL_OFFER,'ma.status'=>MerchantActivity::STATUS_USABLE])
            ->groupBy('ma.id');
        list($count,$list)=Pager::instance($query,$pageSize)->get($page);
        $res=[];
        foreach ($list as $item){
            if($item['rank_id']==='' || $item['rank_id']===null){
                $memberRank='不限';
            }else if(in_array(0,explode(',',$item['rank_id']))){
                $memberRank=trim('非会员,'.$item['rank_name'],',');
            }else{
                $memberRank=$item['rank_name'];
            }
            $res[]=[
                'id'=>$item['id'],
                'name'=>$item['name'],
                'discount'=>$item['discount'],
                'memberRank'=>$memberRank
            ];
        }
        return \Yii::$app->responseHelper->success([
            'totalCount'=>$count,
            'list'=>$res
        ])->response();
    }

    /**
     * 折扣价列表
     * @return mixed
     */
    public function actionFullCutList(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $query=MerchantActivity::find()->alias('ma')
            ->select('ma.*,group_concat(mac.condition_identity) as rank_id,group_concat(mmrd.name) as rank_name,mac1.condition_identity as consumption_full')
            ->leftJoin(MerchantActivityCondition::tableName().' mac','ma.id=mac.active_id and mac.type=:rankType',[
                ':rankType'=>MerchantActivityCondition::TYPE_MEMBER_RANK
            ])
            ->leftJoin(MerchantMemberRankDivide::tableName().' mmrd','mac.condition_identity=mmrd.id')
            ->leftJoin(MerchantActivityCondition::tableName().' mac1','ma.id=mac1.active_id and mac1.type=:consumptionType',[
                ':consumptionType'=>MerchantActivityCondition::TYPE_CONSUMPTION_FULL
            ])
            ->where(['ma.mch_id'=>$mchId,'ma.type'=>MerchantActivity::TYPE_FULL_CUT,'ma.status'=>MerchantActivity::STATUS_USABLE])
            ->groupBy('ma.id');
        list($count,$list)=Pager::instance($query,$pageSize)->get($page);
        $res=[];
        foreach ($list as $item){
            if($item['rank_id']==='' || $item['rank_id']===null){
                $memberRank='不限';
            }else if(in_array(0,explode(',',$item['rank_id']))){
                $memberRank=trim('非会员,'.$item['rank_name'],',');
            }else{
                $memberRank=$item['rank_name'];
            }
            $res[]=[
                'id'=>$item['id'],
                'name'=>$item['name'],
                'rule'=>'满'.$item['consumption_full'].'减'.$item['discount'],
                'memberRank'=>$memberRank
            ];
        }
        return \Yii::$app->responseHelper->success([
            'totalCount'=>$count,
            'list'=>$res
        ])->response();
    }
    /**
     * 修改折扣活动
     * @return mixed
     */
    public function actionEditDiscount(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        $name=\Yii::$app->requestHelper->post('name','');
        $discount=\Yii::$app->requestHelper->post('discount',0,'float');
        $memberRank=\Yii::$app->requestHelper->post('memberRank',[],'array');
        $mark=\Yii::$app->requestHelper->post('mark','');
        $rooms=\Yii::$app->requestHelper->post('rooms',[],'array');
        if(empty($name) || empty($discount) || empty($rooms)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $condition=$this->buildConditions($memberRank,$rooms);
        return $this->editActivity($mchId,$id,MerchantActivity::TYPE_DISCOUNT,$name,$discount,$mark,$condition);
    }

    /**
     * 编辑消费满活动
     * @return mixed
     */
    public function actionEditFullCut(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        $name=\Yii::$app->requestHelper->post('name','');
        $fullCut=\Yii::$app->requestHelper->post('fullCut',0,'float');
        $discount=\Yii::$app->requestHelper->post('discount',0,'float');
        $memberRank=\Yii::$app->requestHelper->post('memberRank',[],'array');
        $mark=\Yii::$app->requestHelper->post('mark','');
        $rooms=\Yii::$app->requestHelper->post('rooms',[],'array');
        if(empty($name) || empty($discount) || empty($rooms) || $fullCut<=0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $condition=$this->buildConditions($memberRank,$rooms);
        $condition[]=['type'=>MerchantActivityCondition::TYPE_CONSUMPTION_FULL,'condition_identity'=>$fullCut];
        return $this->editActivity($mchId,$id,MerchantActivity::TYPE_FULL_CUT,$name,$discount,$mark,$condition);
    }

    /**
     * 编辑特价活动
     * @return mixed
     */
    public function actionEditSpecialPrice(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        $name=\Yii::$app->requestHelper->post('name','');
        $discount=\Yii::$app->requestHelper->post('discount',0,'float');
        $memberRank=\Yii::$app->requestHelper->post('memberRank',[],'array');
        $mark=\Yii::$app->requestHelper->post('mark','');
        $rooms=\Yii::$app->requestHelper->post('rooms',[],'array');
        if(empty($name) || empty($discount) || empty($rooms)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $condition=$this->buildConditions($memberRank,$rooms);
        return $this->editActivity($mchId,$id,MerchantActivity::TYPE_SPECIAL_OFFER,$name,$discount,$mark,$condition);
    }
    /**
     * 组装活动条件
     * @param $memberRank
     * @param $rooms
     * @return array
     */
    protected function buildConditions($memberRank,$rooms){
        $condition=[];
        if(!empty($memberRank)){
            foreach ($memberRank as $rank){
                $condition[]=[
                    'type'=>MerchantActivityCondition::TYPE_MEMBER_RANK,
                    'condition_identity'=>$rank
                ];
            }
        }
        if(!empty($rooms)){
            foreach ($rooms as $room){
                $condition[]=[
                    'type'=>MerchantActivityCondition::TYPE_ROOM_ID,
                    'condition_identity'=>$room
                ];
            }
        }
        $condition[]=['type'=>MerchantActivityCondition::TYPE_FEE_TYPE,'condition_identity'=>1];
        return $condition;
    }

    /**
     * 编辑活动
     * @param $mchId
     * @param $id
     * @param $type
     * @param $name
     * @param $discount
     * @param $mark
     * @param $condition
     * @return mixed
     */
    protected function editActivity($mchId,$id,$type,$name,$discount,$mark,$condition){
        $transaction=\Yii::$app->db->beginTransaction();
        if($id<1){
            $model=new MerchantActivity();
            $model->mch_id=$mchId;
            $model->create_time=$_SERVER['REQUEST_TIME'];
            $model->status=MerchantActivity::STATUS_USABLE;
            $model->type=$type;
            $model->discount=$discount;
            $model->name=$name;
            $model->mark=$mark;
            if(!$model->insert()){
                $transaction->rollBack();
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
            }
            $id=$model->id;
        }else{
            $model=MerchantActivity::findOne(['id'=>$id,'mch_id'=>$mchId,'type'=>$type]);
            if(empty($model)){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
            }
            $model->discount=$discount;
            $model->name=$name;
            $model->mark=$mark;
            $model->create_time=$_SERVER['REQUEST_TIME'];
            if(!$model->update()){
                $transaction->rollBack();
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL)->response();
            }
            MerchantActivityCondition::deleteAll(['active_id'=>$id]);
        }
        if($this->addConditions($id,$condition)){
            $transaction->commit();
            return \Yii::$app->responseHelper->success(['id'=>$id])->response();
        }else{
            $transaction->rollBack();
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_OPERATE_FAIL)->response();
        }
    }

    /**
     * 插入条件
     * @param $activeId
     * @param $conditions
     * @return bool
     */
    protected function addConditions($activeId,$conditions){
        $model=new MerchantActivityCondition();
        foreach ($conditions as $condition){
            $model->setIsNewRecord(true);
            $model->setOldAttributes(null);
            $model->active_id=$activeId;
            $model->type=$condition['type'];
            $model->condition_identity=$condition['condition_identity'];
            if(!$model->insert()){
                return false;
            }
        }
        return true;
    }

    /**
     * 活动详情
     * @return mixed
     */
    public function actionInfo(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        $active=MerchantActivity::find()
            ->where(['status'=>MerchantActivity::STATUS_USABLE,'id'=>$id,'mch_id'=>$mchId])
            ->asArray()->one();
        if(empty($active)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $conditions=MerchantActivityCondition::find()->where(['active_id'=>$id])->asArray()->all();
        $active['rooms']=[];
        $active['memberRank']=[];
        foreach ($conditions as $condition){
            if($condition['type']==MerchantActivityCondition::TYPE_ROOM_ID){
                $active['rooms'][]=$condition['condition_identity'];
            }else if($condition['type']==MerchantActivityCondition::TYPE_MEMBER_RANK){
                $active['memberRank'][]=$condition['condition_identity'];
            }else if($condition['type']==MerchantActivityCondition::TYPE_CONSUMPTION_FULL){
                $active['consumptionFull']=$condition['condition_identity'];
            }
        }
        switch ($active['type']){
            case MerchantActivity::TYPE_DISCOUNT:
                $active['type']='折扣活动';
                break;
            case MerchantActivity::TYPE_SPECIAL_OFFER:
                $active['type']='特价优惠';
                break;
            case MerchantActivity::TYPE_FULL_CUT:
                $active['type']='满减';
                break;
            default:
                $active['type']='优惠';
        }
        return \Yii::$app->responseHelper->success($active)->response();
    }

    /**
     * 删除活动
     * @return mixed
     */
    public function actionDel(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        $active=MerchantActivity::findOne(['status'=>MerchantActivity::STATUS_USABLE,'id'=>$id,'mch_id'=>$mchId]);
        if(empty($active)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $active->status=MerchantActivity::STATUS_DISABLE;
        if($active->update()){
            return \Yii::$app->responseHelper->success()->response();
        }else{
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_FAIL)->response();
        }
    }

    /**
     * 生成计划
     * @return mixed
     */
    public function actionAddPlan(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        $active=MerchantActivity::findOne(['status'=>MerchantActivity::STATUS_USABLE,'id'=>$id,'mch_id'=>$mchId]);
        $start=\Yii::$app->requestHelper->post('start',[],'int');
        $end=\Yii::$app->requestHelper->post('end',[],'int');
        if(empty($active) || empty($start) || empty($end) || $start>$end){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $span=MerchantActiveDate::find()->where('active_id = :activeId and active_date between :start and :end',[
            ':activeId'=>$id,
            ':start'=>intval(date('Ymd',$start)),
            ':end'=>intval(date('Ymd',$end))
        ])->one();
        if($span){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $transaction=\Yii::$app->db->beginTransaction();
        $model=new MerchantActiveDate();
        for($i=$start;$i<=$end;$i+=86400){
            $model->setIsNewRecord(true);
            $model->setAttributes([
                'active_id'=>$id,
                'active_date'=>intval(date('Ymd',$i))
            ]);
            if(!$model->insert(true,['active_id','active_date'])){
                $transaction->rollBack();
                return  \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL)->response();
            }
        }
        $transaction->commit();
        return  \Yii::$app->responseHelper->success()->response();
    }

    /**
     * 删除计划
     * @return mixed
     */
    public function actionDelPlan(){
        $planId=\Yii::$app->requestHelper->post('planId',0,'int');
        $activeId=\Yii::$app->requestHelper->post('activeId',0,'int');
        $plan=MerchantActiveDate::findOne(['active_id'=>$activeId,'id'=>$planId]);
        if($plan && !$plan->delete()){
            return  \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_FAIL)->response();
        }else{
            return  \Yii::$app->responseHelper->success()->response();
        }
    }

    /**
     * 计划列表
     * @return mixed
     */
    public function actionPlans(){
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $activeId=\Yii::$app->requestHelper->post('activeId',0,'int');
        $query=MerchantActiveDate::find()
            ->where(['active_id'=>$activeId])
            ->orderBy('active_date desc');
        list($count,$list)=Pager::instance($query,$pageSize)->get($page);
        $res=[];
        foreach ($list as $item){
            $res[]=[
                'id'=>$item['id'],
                'activeDate'=>date('Y-m-d',strtotime($item['active_date']))
            ];
        }
        return \Yii::$app->responseHelper->success([
            'totalCount'=>$count,
            'list'=>$res
        ])->response();
    }


    public function actionRoomsByTree(){
        $mch=\Yii::$app->user->getAdmin()->getMerchant();
        $mchId=$mch->getId();
        $premisesId=$mch->getPremise()->id;
        $activityId=\Yii::$app->requestHelper->post('activeId',0,'int');
        $query=Room::find()->alias('r')
            ->select('r.id,r.number,r.type,rt.name as type_name')
            ->leftJoin(RoomType::tableName().' rt','r.type=rt.id')
            ->where(['r.mch_id'=>$mchId,'r.premises_id'=>$premisesId])
            ->orderBy('r.number asc');
        if($activityId>0){
            $selectedRooms=MerchantActivityCondition::find()
                ->select('condition_identity')
                ->where(['active_id'=>$activityId,'type'=>MerchantActivityCondition::TYPE_ROOM_ID])
                ->asArray()->column();
        }else{
            $selectedRooms=[];
        }
        $rooms=$query->asArray()->all();
        $res=[];
        $index=[];
        foreach ($rooms as $room){
            if(!isset($index[$room['type']])){
                $res[]=[
                    'title'=>$room['type_name'],
                    'type'=>1,
                    'id'=>$room['type'],
                    'children'=>[]
                ];
                $index[$room['type']]=count($res)-1;
            }
            $res[$index[$room['type']]]['children'][]=[
                'title'=>$room['number'],
                'type'=>2,
                'id'=>$room['id'],
                'checked'=>in_array($room['id'],$selectedRooms)
            ];
        }
        return \Yii::$app->responseHelper->success([
            ['title'=>'全部房间','expand'=>true,'children'=>$res]
        ])->response();
    }
}