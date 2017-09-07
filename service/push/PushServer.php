<?php
namespace service\push;
use common\components\BaseServer;
use common\components\ErrorManager;
use common\components\validator\SchoolVisitRight;
use common\models\Messages;
use common\models\Persons;
use common\models\Push;

class PushServer extends BaseServer{
    const CONFIG_KEY='mj_no_push';
    protected $msgModelClass;
    protected $pushModelClass;
    protected $attributes;
    protected $pushUser;
    public function __construct()
    {
        $this->msgModelClass=Messages::className();
        $this->pushModelClass=Push::className();
    }

    public function bindAttribute(AttributesAbstract $attributes){
        $this->attributes=$attributes;
        return $this;
    }

    public function bindPushUser(UserDataAbstract $pushUser){
        $this->pushUser=$pushUser;
        return $this;
    }
    protected function newInstanceMsgModel(){
        $modelClass=$this->msgModelClass;
        return new $modelClass();
    }

    protected function newInstancePushModel(){
        $modelClass=$this->pushModelClass;
        return new $modelClass();
    }

    protected function getMsgModel(){
        static $model;
        if($model===null){
            $model=$this->newInstanceMsgModel();
        }else{
            $model->setOldAttributes(null);
        }
        return $model;
    }

    protected function getPushModel(){
        static $model;
        if($model===null){
            $model=$this->newInstancePushModel();
        }else{
            $model->setOldAttributes(null);
        }
        return $model;
    }
    public function getSchoolId(){
        static $schoolId;
        if($schoolId===null){
            if($this->pushUser instanceof PersonData){
                $schoolId= Persons::getBelongToSchool($this->pushUser->personId);
            }else{
                $schoolId= $this->pushUser->schoolId;
            }
        }
        return $schoolId;
    }

    public function check(){
        return true;
    }
    public function send(){
        if(\Yii::$app->user->getValidator(SchoolVisitRight::className())->setVisitSchoolId($this->getSchoolId())->hasVisitRight()===false){
            \Yii::$app->errorManager->generateError(ErrorManager::ERROR_RIGHT_NO_VISIT_SCHOOL);
            return false;
        }
        if($this->check()==false){
            \Yii::$app->errorManager->generateError(ErrorManager::ERROR_PUSH_NO_RIGHT);
            return false;
        }
        $appUsers=$this->pushUser->getPushUsers();
        if(empty($appUsers)){
            return true;
        }
        $transaction=\Yii::$app->db->beginTransaction();
        $msgId=$this->addMessage();
        if($msgId===false){
            $transaction->rollBack();
            return false;
        }
        foreach ($appUsers as $appUser){
            $res=$this->addPush($appUser,$msgId);
            if($res===false){
                $transaction->rollBack();
                return false;
            }
        }
        $transaction->commit();
        return true;
    }

    protected function addMessage(){
        $model=$this->getMsgModel();
        $attributes=$this->attributes->getMessageAttributes();
        $model->setAttributes($attributes);
        if($model->insert(true,array_keys($attributes))===false){
            \Yii::$app->errorManager->generateError(ErrorManager::ERROR_MSG_ADD_FAIL);
            return false;
        }else{
            return $model->getAttribute('message_id');
        }
    }

    protected function addPush($appUserId,$messageId){
        $model=$this->getPushModel();
        $attributes=$this->attributes->getPushAttributes($appUserId,$messageId);
        $model->setAttributes($attributes);
        if($model->insert(true,array_keys($attributes))){
            return true;
        }else{
            \Yii::$app->errorManager->generateError(ErrorManager::ERROR_PUSH_ADD_FAIL);
            return false;
        }
    }
}