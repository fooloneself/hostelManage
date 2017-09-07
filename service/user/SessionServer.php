<?php
namespace service\user;
use common\components\BaseServer;
use common\models\Cookie;

class SessionServer extends BaseServer{
    public $expire=86400;
    private $_token;
    private $_userId;
    private $_session;
    public function __construct($userId,$token)
    {
        $this->_userId=$userId;
        $this->_token=$token;
    }

    /**
     * 刷新会话信息
     */
    public function refresh(){
        $this->_session=Cookie::getSessionInfo($this->_userId);
    }

    /**
     * 获取会话
     * @param null $name
     * @return null
     */
    public function getSession($name=null){
        if($this->_session===null){
            $this->refresh();
        }
        if($name){
            return isset($this->_session[$name])?$this->_session[$name]:null;
        }else{
            return $this->_session;
        }
    }

    /**
     * 获取cookie的uuid
     * @return null
     */
    public function getUUid(){
        return $this->getSession('cookie_uuid');
    }
    /**
     * 设置会话属性
     * @param $name
     * @param null $value
     * @return $this
     */
    public function setSession($name,$value=null){
        if(is_array($name)){
            $this->_session=array_merge($this->_session,$name);
        }else{
            $this->_session[$name]=$value;
        }
        return $this;
    }

    /**
     * 设置推送ID
     * @param $pushId
     * @return $this
     */
    public function setPushId($pushId){
        $this->setSession('pushid',$pushId);
        return $this;
    }

    /**
     * 设置请求终端类型
     * @param $clientType
     * @return $this
     */
    public function setClientType($clientType){
        $this->setSession('clientType',$clientType);
        return $this;
    }
    /**
     * 生成会话令牌
     * @return string
     */
    public static function createToken(){
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            $uuid = substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12);

            return $uuid;
        }
    }
    /**
     * 保存
     * @return bool
     */
    public function save(){
        $model=new Cookie();
        $session=$this->_session;
        $session['uid']=$this->_userId;
        $session['logindate']=time();
        $session['cookie_uuid']=$this->_token;
        $model->setAttributes($session);
        return $model->save();
    }
    /**
     * 判断登录是否过期
     * @return bool
     */
    public function isOverdue(){
        if(empty($this->_session)){
            return true;
        }
        $tokenTime = intval($this->getSession('logindate')) + $this->expire;
        if (time() > $tokenTime) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否在其他设备上登录
     * @return bool
     */
    public function isLoginOnOtherDevice(){
        if ($this->_token != $this->getSession('cookie_uuid')) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否在登录状态
     * @return bool
     */
    public function isLogin(){
        if($this->isOverdue() || $this->isLoginOnOtherDevice()){
            return false;
        }else{
            return true;
        }
    }
}