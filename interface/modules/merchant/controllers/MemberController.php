<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Dictionary;
use common\models\DictionaryItem;
use common\models\MemberRankDivide;
use common\models\MerchantMember;

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
        $wxAccount=\Yii::$app->requestHelper->post('wxAccount','','string');
        $mark=\Yii::$app->requestHelper->post('mark','','string');
        $memberId=\Yii::$app->requestHelper->post('id',0,'int');
        $mchId=\Yii::$app->user->getAdmin()->getMchId();
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
                'wx_account'=>$wxAccount,
                'mark'=>$mark,
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
                'wx_account'=>$wxAccount,
                'mark'=>$mark,
                'create_time'=>time(),
                'consumption_amount'=>0,
                'rank'=>0,
                'integral'=>0
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
                    'wxAccount'=>$member['wx_account'],
                    'mark'=>$member['mark']
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
        $query=MerchantMember::find()->where(['mch_id'=>$mchId]);
        $count=intval($query->count());
        $list=$query->offset(($page-1)*$pageSize)->limit($pageSize)->asArray()->all();
        $res=[];
        foreach ($list as $item){
            $res[]=[
                'id'=>$item['id'],
                'name'=>$item['name'],
                'birthday'=>$item['birthday']>0?date('Y-m-d',$item['birthday']):'',
                'mobile'=>$item['mobile'],
                'wx_account'=>$item['wx_account'],
                'rank'=>$item['rank'],
                'consumption_amount'=>$item['consumption_amount'],
                'integral'=>$item['integral'],
                'register_date'=>date('Y-m-d H:i:s',$item['create_time']),
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
            $rank=MemberRankDivide::findOne(['id'=>$id,'mch_id'=>$mchId]);
            if(empty($rank)){
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_WRONG,'未找到等级划分')->response();
            }
            $rank->name=$name;
            $rank->min_consumption_amount=$minConsumptionAmount;
            $rank->min_integral=$minIntegral;
            $rank->mark=$mark;
            if($rank->update(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL,'等级划分保存失败')->response();
            }
        }else{
            $rank=new MemberRankDivide();
            $rank->create_time=time();
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
}