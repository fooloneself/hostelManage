<?php
namespace service\user;
use common\components\BaseServer;
use common\models\AppUser;

class UserServer extends BaseServer{
    private $_userId;
    private $_userInfo;
    private $_hasRefresh=false;
    public function __construct($userId)
    {
        $this->_userId=$userId;
    }
    /**
     * 更新APP账号信息
     */
    public function refresh(){
        $this->_userInfo=AppUser::getUserInfo($this->_userId);
        $this->_hasRefresh=true;
        return $this;
    }

    /**
     * 获取APP账号信息
     * @param null $name
     * @return null
     */
    public function getAppAccountInfo($name=null){
        if($this->_hasRefresh===false){
            $this->refresh();
        }
        if($this->_userInfo==null){
            return null;
        }
        if($name){
            return $this->_userInfo->getAttribute($name);
        }else{
            return $this->_userInfo->getAttributes();
        }
    }

    /**
     * 判断账号是否存在
     * @return bool
     */
    public function isExists(){
        return !empty($this->getAppAccountInfo());
    }

    /**
     * 判断账号是否冻结
     * @return bool
     */
    public function isFrozen(){
        return $this->getAppAccountInfo('disable')==1?true:false;
    }

    /**
     * 更新登录信息
     * @param $lastLoginTime
     * @param $version
     */
    public function refreshLoginInfo($lastLoginTime,$version){
        $model=new AppUser();
        $model->app_user_id=$this->_userId;
        $model->last_login_time=$lastLoginTime;
        $model->last_login_version=$version;
        $model->save(false,['last_login_time','last_login_version']);
    }
}