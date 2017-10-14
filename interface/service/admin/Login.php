<?php
namespace service\admin;
use common\components\ErrorManager;
use common\components\Server;

class Login extends Server {
    public $userName;
    public $password;
    protected $admin;
    protected $cookie;
    public function __construct($userName,$password){
        $this->userName=$userName;
        $this->password=$password;
    }

    public function exe(){
        $admin=Admin::byUserName($this->userName);
        if(!$admin->isExists()){
            $this->setError(ErrorManager::ERROR_USER_NOT_EXISTS,'账号或密码错误');
            return false;
        }else if(!$admin->isEqualToPwd($this->password)){
            $this->setError(ErrorManager::ERROR_PWD_ERROR,'账号或密码错误');
            return false;
        }else{
            $admin->resetToken();
            return $admin;
        }
    }
}