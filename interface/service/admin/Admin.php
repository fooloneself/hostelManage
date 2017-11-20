<?php
namespace service\admin;
use common\components\ErrorManager;
use common\components\Server;
use common\library\Helper;
use service\merchant\Merchant;

class Admin extends Server{
    //admin model
    protected $admin;

    /**
     * 加载 admin model
     * @param \common\models\Admin $admin
     * @return $this
     */
    public function load(\common\models\Admin $admin){
        $this->admin=$admin;
        return $this;
    }

    /**
     * 通过账户号获取
     * @param $userName
     * @return static
     */
    public static function byUserName($userName){
        $server=new static();
        $model=\common\models\Admin::findOne(['user_name'=>$userName]);
        if(empty($model))$model=new \common\models\Admin();
        $server->load($model);
        return $server;
    }

    /**
     * 通过id获取
     * @param $id
     * @return static
     */
    public static function byId($id){
        $server=new static();
        $model=\common\models\Admin::findOne(['id'=>$id]);
        if(empty($model))$model=new \common\models\Admin();
        $server->load($model);
        return $server;
    }

    /**
     * 通过token获取
     * @param $token
     * @return static
     */
    public static function byToken($token){
        $server=new static();
        $model=\common\models\Admin::findOne(['token'=>$token]);
        if(empty($model))$model=new \common\models\Admin();
        $server->load($model);
        return $server;
    }

    /**
     * 用户是否存在
     * @return bool
     */
    public function isExists(){
        $userName=$this->getUserName();
        return !empty($userName);
    }

    /**
     * 获取账户名
     * @return mixed
     */
    public function getUserName(){
        return $this->admin->getAttribute('user_name');
    }

    /**
     * 获取商户id
     * @return int
     */
    public function getMchId(){
        return intval($this->admin->getAttribute('mch_id'));
    }

    /**
     * 是否是超级管理员
     * @return bool
     */
    public function isSuper(){
        return ($this->admin->getAttribute('is_super')==1)? true: false;
    }

    /**
     * 获取会话令牌
     * @return mixed
     */
    public function getToken(){
        return $this->admin->getAttribute('token');
    }

    /**
     * 是否过期
     * @return bool
     */
    public function isExpire(){
        return (intval($this->admin->getAttribute('expire'))<intval($_SERVER['REQUEST_TIME']));
    }

    /**
     * 是否登录
     * @return bool
     */
    public function isLogin(){
        $token=$this->getToken();
        return !empty($token);
    }
    /**
     * 重置会话令牌
     * @return $this
     */
    public function resetToken(){
        $this->admin->setAttribute('token',$this->makeToken());
        $this->admin->setAttribute('last_login_time',$_SERVER['REQUEST_TIME']);
        $this->admin->setAttribute('expire',intval($_SERVER['REQUEST_TIME'])+\Yii::$app->params['expire_time']);
        $this->admin->save(true,['token','last_login_time','expire']);
        return $this;
    }

    /**
     * 生成会话令牌
     * @return string
     */
    protected function makeToken(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
            return $uuid;
        }
    }

    /**
     * 是否是商户的平台管理员
     * @return bool
     */
    public function isAdminOfMerchant(){
        return $this->getMchId()!=0;
    }

    /**
     * 获取商户
     * @return bool|Merchant
     */
    public function getMerchant(){
        if($this->isAdminOfMerchant()){
            return Merchant::byId($this->getMchId());
        }else{
            return false;
        }
    }

    /**
     * 获取密码
     * @return mixed
     */
    public function getPassword(){
        return $this->admin->getAttribute('password');
    }

    /**
     * 获取权限
     * @return Privilege
     */
    public function getPrivilege(){
        return new Privilege($this);
    }

    /**
     * 获取管理员id
     * @return int
     */
    public function getAdminId(){
        return intval($this->admin->getAttribute('id'));
    }

    /**
     * 获取所有模块
     * @return mixed
     */
    public function allModules(){
        if($this->isAdminOfMerchant()){
            return $this->getMerchant()->allModule();
        }else{
            return \Yii::$app->getModule('backend')->allModule();
        }
    }

    /**
     * 所有模块及标注
     * @return null
     */
    public function allModuleLabels(){
        if($this->isAdminOfMerchant()){
            return $this->getMerchant()->allModuleLabel();
        }else{
            return \Yii::$app->getModule('backend')->allModuleLabels();
        }
    }

    /**
     * 是否拥有模块
     * @param $module
     * @return bool
     */
    public function hasModule($module){
        $modules=$this->allModules();
        return in_array($module,$modules);
    }

    /**
     * 是否绑定商户
     * @return bool
     */
    public function hasBindMerchant(){
        return $this->getMchId()>0;
    }

    /**
     * 绑定商户
     * @param Merchant $merchant
     * @return bool
     */
    public function bindMerchant(Merchant $merchant){
        $this->admin->mch_id=$merchant->getId();
        if($this->admin->save(false,['mch_id'])){
            return true;
        }else{
            $this->setError(ErrorManager::ERROR_UPDATE_FAIL,'商户绑定失败');
            return false;
        }
    }

    /**
     * 校验密码是否相同
     * @param $pwd
     * @return bool
     */
    public function isEqualToPwd($pwd){
        return $this->getPassword()==Helper::encryptPwd($pwd);
    }

    /**
     * 重设密码
     * @param $pwd
     * @return mixed
     */
    public function resetPwd($pwd){
        $this->admin->setAttribute('password',Helper::encryptPwd($pwd));
        return $this->admin->save(false,['password']);
    }

    /**
     * 退出
     * @return $this
     */
    public function loginOut(){
        $this->admin->setAttribute('token','');
        $this->admin->save(false,['token']);
        return $this;
    }
}