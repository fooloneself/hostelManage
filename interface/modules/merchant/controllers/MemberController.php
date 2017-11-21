<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Dictionary;
use common\models\DictionaryItem;
use common\models\MemberRankDivide;
use common\models\MerchantMember;
use common\models\MerchantMemberRankDivide;
use service\Pager;

class MemberController extends Controller{

    /**
     * 新增、修改会员
     * @return mixed
     */
    public function actionRecord(){
        $name=\Yii::$app->requestHelper->post('name','','string');
        $mobile=\Yii::$app->requestHelper->post('mobile','','string');
        $numberType=\Yii::$app->requestHelper->post('numberType','','string');
        $number=\Yii::$app->requestHelper->post('number','','string');
        $sex=\Yii::$app->requestHelper->post('sex',0,'int');
        $birthday=\Yii::$app->requestHelper->post('birthday',0,'int');
        $mark=\Yii::$app->requestHelper->post('mark','','string');
        $memberId=\Yii::$app->requestHelper->post('id',0,'int');
        $rank=\Yii::$app->requestHelper->post('rank',0,'int');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $balance=\Yii::$app->requestHelper->post('balance',0,'float');
        if($memberId>0){
            $member=MerchantMember::findOne(['id'=>$memberId,'mch_id'=>$mchId]);
            if(empty($member)){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'未找到会员')->response();
            }
            $member->setAttributes([
                'name'=>$name,
                'mobile'=>$mobile,
                'number_type'=>$numberType,
                'number'=>$number,
                'sex'=>$sex,
                'birthday'=>$birthday,
                'mark'=>$mark,
                'rank'=>$rank,
                'balance'=>$balance
            ]);
            if($member->update(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL,'会员信息修改失败')->response();
            }
        }else{
            $member=new MerchantMember();
            $member->setAttributes([
                'mch_id'=>$mchId,
                'name'=>$name,
                'mobile'=>$mobile,
                'number_type'=>$numberType,
                'number'=>$number,
                'sex'=>$sex,
                'birthday'=>$birthday,
                'mark'=>$mark,
                'create_time'=>time(),
                'consumption_amount'=>0,
                'integral'=>0,
                'rank'=>$rank,
                'balance'=>$balance
            ]);
            if(!$member->insert(false)){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL,'新增会员失败')->response();
            }else{
                return \Yii::$app->responseHelper->success()->response();
            }
        }
    }

    /**
     * 编辑会员页面-基本信息
     * @return mixed
     */
    public function actionEditPageInfo(){
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $sex=DictionaryItem::find()->where(['code'=>Dictionary::DICTIONARY_SEX])->asArray()->all();
        $numberType=DictionaryItem::find()->where(['code'=>Dictionary::DICTIONARY_NUMBER_TYPE])->asArray()->all();
        $res=null;
        if($id>0){
            $member=MerchantMember::find()->where(['id'=>$id,'mch_id'=>$mchId])->asArray()->one();
            if(!empty($member)){
                $res=[
                    'name'=>$member['name'],
                    'mobile'=>$member['mobile'],
                    'numberType'=>$member['number_type'],
                    'number'=>$member['number'],
                    'sex'=>$member['sex'],
                    'birthday'=>$member['birthday']>0?date('Y-m-d',$member['birthday']): '',
                    'mark'=>$member['mark'],
                    'balance'=>floatval($member['balance']),
                    'rank'=>$member['rank']
                ];
            }
        }
        return \Yii::$app->responseHelper->success([
            'sex'=>$sex,
            'numberType'=>$numberType,
            'member'=>$res
        ])->response();
    }

    /**
     * 会员列表
     * @return mixed
     */
    public function actionList(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $rank=\Yii::$app->requestHelper->post('rank',0,'int');
        $search=\Yii::$app->requestHelper->post('search','');
        $query=MerchantMember::find()
            ->alias('mm')
            ->select('mm.*,mrd.name as rank_name')
            ->leftJoin(MerchantMemberRankDivide::tableName().' mrd','mm.rank=mrd.id')
            ->where(['mm.mch_id'=>$mchId]);
        if($rank>0){
            $query->andWhere(['mm.rank'=>$rank]);
        }
        if(!empty($search)){
            $query->andWhere(['or','mm.mobile'=>$search,'mm.name'=>$search]);
        }
        list($count,$list)=Pager::instance($query,$pageSize)->get($page);
        $res=[];
        foreach ($list as $item){
            $res[]=[
                'id'=>$item['id'],
                'name'=>$item['name'],
                'birthday'=>$item['birthday']>0?date('Y-m-d',$item['birthday']):'',
                'mobile'=>$item['mobile'],
                'rank'=>$item['rank_name'],
                'consumption_amount'=>$item['consumption_amount'],
                'integral'=>$item['integral'],
                'register_date'=>date('Y-m-d H:i:s',$item['create_time']),
                'balance'=>$item['balance']
            ];
        }
        return \Yii::$app->responseHelper->success([
            'totalCount'=>$count,
            'list'=>$res
        ])->response();
    }

    /**
     * 删除会员
     * @return mixed
     */
    public function actionDelete(){
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        if($id<=0){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $member=MerchantMember::findOne(['id'=>$id,'mch_id'=>$mchId]);
        if($member && !$member->delete()){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_DELETE_FAIL)->response();
        }else{
            return \Yii::$app->responseHelper->success()->response();
        }
    }
    /**
     * 会员等级划分
     * @return mixed
     */
    public function actionRankDivide(){
        $name=\Yii::$app->requestHelper->post('name','','string');
        $minConsumptionAmount=\Yii::$app->requestHelper->post('minConsumptionAmount',0,'float');
        $minIntegral=\Yii::$app->requestHelper->post('minIntegral',0,'float');
        $mark=\Yii::$app->requestHelper->post('mark','','string');
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        if($id>0){
            $rank=MerchantMemberRankDivide::findOne(['id'=>$id,'mch_id'=>$mchId]);
            if(empty($rank)){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'未找到等级划分')->response();
            }
            $rank->name=$name;
            $rank->min_consumption_amount=$minConsumptionAmount;
            $rank->min_integral=$minIntegral;
            $rank->mark=$mark;
            $rank->update_time=$_SERVER['REQUEST_TIME'];
            if($rank->update(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL,'等级划分保存失败')->response();
            }
        }else{
            $rank=new MerchantMemberRankDivide();
            $rank->mch_id=$mchId;
            $rank->create_time=$_SERVER['REQUEST_TIME'];
            $rank->update_time=$_SERVER['REQUEST_TIME'];
            $rank->name=$name;
            $rank->min_consumption_amount=$minConsumptionAmount;
            $rank->min_integral=$minIntegral;
            $rank->mark=$mark;
            if($rank->insert(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL,'新增等级划分失败')->response();
            }
        }
    }

    /**
     * 等级列表
     * @return mixed
     */
    public function actionRanks(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $page=\Yii::$app->requestHelper->post('page',1,'int');
        $pageSize=\Yii::$app->requestHelper->post('pageSize',10,'int');
        $query=MerchantMemberRankDivide::find()
            ->select('id,name,min_consumption_amount,min_integral,mark')
            ->where(['mch_id'=>$mchId]);
        list($count,$list)=Pager::instance($query,$pageSize)->get($page);
        return \Yii::$app->responseHelper->success([
            'totalCount'=>$count,
            'list'=>$list
        ])->response();
    }

    /**
     * 等级详情
     * @return mixed
     */
    public function actionRank(){
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        if($id<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $rank=MerchantMemberRankDivide::find()->where(['id'=>$id,'mch_id'=>$mchId])->asArray()->one();
        if(empty($rank)){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        return \Yii::$app->responseHelper->success([
            'id'=>intval($rank['id']),
            'name'=>$rank['name'],
            'minConsumptionAmount'=>$rank['min_consumption_amount'],
            'minIntegral'=>$rank['min_integral'],
            'mark'=>$rank['mark']
        ])->response();
    }

    /**
     * 删除会员等级
     * @return mixed
     */
    public function actionDelRank(){
        $id=\Yii::$app->requestHelper->post('id',0,'int');
        if($id<1){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $rank=MerchantMemberRankDivide::findOne(['id'=>$id,'mch_id'=>$mchId]);
        if($rank && !$rank->delete()){
            return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG)->response();
        }
        return \Yii::$app->responseHelper->success()->response();
    }

    /**
     * 所有会员等级
     * @return mixed
     */
    public function actionAllRank(){
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
        $res=MerchantMemberRankDivide::find()
            ->select('id,name')
            ->where(['mch_id'=>$mchId])
            ->asArray()->all();
        return \Yii::$app->responseHelper->success($res)->response();
    }
}