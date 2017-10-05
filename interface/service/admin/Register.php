<?php
namespace service\admin;
use common\components\ErrorManager;
use common\components\Server;
use common\library\Helper;
use common\models\Admin;

class Register extends Server{
    //admin model
    protected $admin;

    public function __construct(Admin $admin)
    {
        $this->admin=$admin;
    }

    /**
     * 注册
     * @return $this|bool
     */
    public function exe(){
        if($this->check()===false)return false;
        $this->admin->password=md5($this->admin->password);
        if($this->admin->insert()){
            return (new \service\admin\Admin())->load($this->admin);
        }else{
            return false;
        }
    }

    /**
     * 校验参数
     * @return bool
     */
    protected function check(){
        if(Helper::checkUsername($this->admin->user_name)===false){
            $this->setError(ErrorManager::ERROR_USER_NAME_FORMAT);
            return false;
        }else if(Helper::checkPwd($this->admin->password)===false){
            $this->setError(ErrorManager::ERROR_PWD_FORMAT);
            return false;
        }else{
            $admin=Admin::findOne(['user_name'=>$this->admin->user_name]);
            if($admin){
                $this->setError(ErrorManager::ERROR_USER_EXISTS);
                return false;
            }
        }
        return true;
    }
}