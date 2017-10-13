<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\models\Member;
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
            $member=Member::findOne(['id'=>$memberId,'mch_id'=>$mchId]);
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
                'mch_id'=>$mchId
            ]);
            if($member->update(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_UPDATE_FAIL,'会员信息修改失败')->response();
            }
        }else{
            $transaction=\Yii::$app->db->beginTransaction();
            $member=new Member();
            $member->setAttributes([
                'name'=>$name,
                'mobile'=>$mobile,
                'number_type'=>$numberType,
                'number'=>$number,
                'sex'=>$sex,
                'birthday'=>$birthday,
                'wx_account'=>$wxAccount,
                'mark'=>$mark,
                'mch_id'=>$mchId,
                'create_time'=>time(),
                'consumption_amount'=>0
            ]);
            if(!$member->insert(false)){
                $transaction->rollBack();
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL,'新增会员失败')->response();
            }
            $merchantMember=new MerchantMember();
            $merchantMember->setAttributes([
                'mch_id'=>$mchId,
                'member_id'=>$member->id,
                'consumption_amount'=>0
            ]);
            if($merchantMember->insert(false)){
                return \Yii::$app->responseHelper->success()->response();
            }else{
                $transaction->rollBack();
                return \Yii::$app->responseHelper->error(ErrorManager::ERROR_INSERT_FAIL,'新增会员失败')->response();
            }
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