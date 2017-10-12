<?php
namespace common\components;
class Error{
    public static $errorMsg=[
        ErrorManager::ERROR_PARAM_UN_FIND=>'缺少必要参数',
        ErrorManager::ERROR_PWD_FORMAT=>'密码格式错误',
        ErrorManager::ERROR_USER_NAME_FORMAT=>'账号格式错误',
        ErrorManager::ERROR_USER_NOT_EXISTS=>'账户不存在',
        ErrorManager::ERROR_PWD_ERROR=>'密码错误',
        ErrorManager::ERROR_USER_FROZEN=>'账户冻结',
        ErrorManager::ERROR_PARAM_TYPE_WRONG=>'请求参数类型错误',
        ErrorManager::ERROR_SYSTEM=>'系统错误',
        ErrorManager::ERROR_INSERT_FAIL=>'数据库插入失败',
        ErrorManager::ERROR_NOT_PRIVILEGE=>'无权访问接口',
        ErrorManager::ERROR_NOT_LOGIN=>'未登录',
        ErrorManager::ERROR_LOGIN_EXPIRE=>'登录已过期',
        ErrorManager::ERROR_INTERFACE_UN_OPEN=>'接口未开放',
        ErrorManager::ERROR_INTERFACE_ERROR=>'接口错误',
        ErrorManager::ERROR_USER_EXISTS=>'账号已存在',
        ErrorManager::ERROR_DICTIONARY_KEY_EXISTS=>'已存在于字典',
        ErrorManager::ERROR_DELETE_FAIL=>'删除失败',
        ErrorManager::ERROR_DICTIONARY_KEY_NOT_EXISTS=>'不存在于字典',
        ErrorManager::ERROR_UPDATE_FAIL=>'修改失败',
        ErrorManager::ERROR_MENU_NOT_EXISTS=>'菜单不存在',
        ErrorManager::ERROR_DELETE_CANNOT=>'不能删除',
        ErrorManager::ERROR_PARAM_WRONG=>'参数错误',
        ErrorManager::ERROR_ACCOUNT_ERROR=>'账号错误',
        ErrorManager::ERROR_MENU_EXISTS=>'菜单已存在',
        ErrorManager::ERROR_CHANNEL_EXISTS=>'渠道已存在',
        ErrorManager::ERROR_CHANNEL_NOT_EXISTS=>'渠道不存在',
        ErrorManager::ERROR_NOT_EXISTS=>'不存在',
        ErrorManager::ERROR_EXISTS=>'已存在',
        ErrorManager::ERROR_OPERATE_FAIL=>'操作失败',
    ];
    //错误码
    public $status;
    //错误信息
    public $msg;

    public function __construct($status=ErrorManager::STATUS_SUCCESS,$msg='')
    {
        $this->status=$status;
        $this->msg=$msg;
    }

    /**
     * 设置错误码
     * */
    public function setStatus($status){
        $this->status=$status;
        return $this;
    }

    /**
     * 设置错误信息
     * @param $msg
     * @return $this
     */
    public function setMsg($msg){
        $this->msg=$msg;
        return $this;
    }

    /**
     * 获取错误码
     * @return int
     */
    public function getStatus(){
        return $this->status;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getMsg(){
        if(empty($this->msg)){
            $this->msg=isset(self::$errorMsg[$this->status])?self::$errorMsg[$this->status]:'';
        }
        return $this->msg;
    }
}