<?php
namespace common\components;
use yii\base\Component;
use common\components\Error;

class ErrorManager extends Component{
    const STATUS_SUCCESS                    =0;
    const ERROR_SYSTEM                      =100000;
    const ERROR_PARAM_UN_FIND              =100001;
    const ERROR_PWD_FORMAT                 =100002;
    const ERROR_USER_NAME_FORMAT            =100003;
    const ERROR_USER_NOT_EXISTS             =100004;
    const ERROR_PWD_ERROR                   =100005;
    const ERROR_USER_FROZEN                 =100006;
    const ERROR_PARAM_TYPE_WRONG            =100007;
    const ERROR_INSERT_FAIL                 =100008;
    const ERROR_NOT_PRIVILEGE               =100009;
    const ERROR_NOT_LOGIN                    =100010;
    const ERROR_LOGIN_EXPIRE                =100011;
    const ERROR_INTERFACE_UN_OPEN           =100012;
    const ERROR_INTERFACE_ERROR             =100013;
    const ERROR_USER_EXISTS                 =1000014;
    const ERROR_DICTIONARY_KEY_EXISTS      =1000015;
    const ERROR_DELETE_FAIL                 =1000016;
    const ERROR_DICTIONARY_KEY_NOT_EXISTS =1000017;
    const ERROR_UPDATE_FAIL                 =1000018;
    const ERROR_MENU_NOT_EXISTS             =1000019;
    const ERROR_DELETE_CANNOT               =1000020;
    const ERROR_PARAM_WRONG                 =1000021;
    const ERROR_ACCOUNT_ERROR                =1000022;
    const ERROR_MENU_EXISTS                 =1000023;
    const ERROR_CHANNEL_EXISTS              =1000024;
    const ERROR_CHANNEL_NOT_EXISTS         =1000025;
    const ERROR_NOT_EXISTS                  =1000026;
    const ERROR_EXISTS                      =1000027;
    const ERROR_OPERATE_FAIL                =1000028;
    //错误信息 common\components\Error的实例
    private $_error;

    /**
     * 获取错误
     * @return mixed
     */
    public function getError(){
        return $this->_error;
    }

    /**
     * 清楚错误
     * @return $this
     */
    public function clear(){
        $this->_error=null;
        return $this;
    }

    /**
     * 添加错误
     * @param \Error $error
     * @return $this
     */
    public function addError(Error $error){
        $this->_error=$error;
        return $this;
    }

    /**
     * 生成错误
     * @param int $status
     * @param string $msg
     * @return \common\components\Error
     */
    public function generateError($status=ErrorManager::STATUS_SUCCESS,$msg=''){
        $error=new Error($status,$msg);
        $this->addError($error);
        return $error;
    }

    /**
     * 是否有错
     * @return bool
     */
    public function hasError(){
        if(empty($this->_errors)){
            return false;
        }else{
            return true;
        }
    }
}