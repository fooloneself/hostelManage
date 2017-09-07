<?php
namespace service\user;
use common\components\BaseServer;

class LoginValidateServer extends BaseServer{
    public $userName;
    public $pwd;
    public $pushId;
    public $version;
    public $clientType;
    private $_user;
    public function __construct($userName,$pwd,$pushId,$clientType,$version=''){
        $this->userName=$userName;
        $this->pwd=$pwd;
        $this->pushId=$pushId;
        $this->version=$version;
        $this->clientType=$clientType;
    }

    /**
     * 获取账户
     * @return bool
     */
    protected function getUser(){
        if($this->_user===null){
            $this->_user=AppUser::getUserByUserName($this->userName);
        }
        return $this->_user;
    }
    /**
     * 校验
     * @return bool
     */
    public function validate(){
        if(empty($this->userName) || empty($this->pwd) || empty($this->pushId)){
            $this->setError(ErrorManager::ERROR_PARAM_UN_FIND);
            return false;
        }else if (!Validate::checkUserName($this->userName)) {
            $this->setError(ErrorManager::ERROR_USER_NAME_FORMAT);
            return false;
        }else if (!Validate::checkPwd($this->pwd)) {
            $this->setError(ErrorManager::ERROR_PWD_FORMAT);
            return false;
        }
        return true;
    }

    /**
     * 校验账户存在和密码正确性
     * @return bool
     */
    public function checkUser(){
        $user=$this->getUser();
        if($user===false){
            $this->setError(ErrorManager::ERROR_USER_NOT_EXISTS);
            return false;
        }else if($user['password']!=$this->pwd){
            $this->setError(ErrorManager::ERROR_PWD_ERROR);
            return false;
        }else if($user['disable']==1){
            $this->setError(ErrorManager::ERROR_USER_FROZEN);
            return false;
        }
        return true;
    }
}