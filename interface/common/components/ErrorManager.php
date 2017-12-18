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
    const ERROR_USER_EXISTS                 =100014;
    const ERROR_DICTIONARY_KEY_EXISTS      =100015;
    const ERROR_DELETE_FAIL                 =100016;
    const ERROR_DICTIONARY_KEY_NOT_EXISTS =100017;
    const ERROR_UPDATE_FAIL                 =100018;
    const ERROR_MENU_NOT_EXISTS             =100019;
    const ERROR_DELETE_CANNOT               =100020;
    const ERROR_PARAM_WRONG                     =100021;
    const ERROR_ACCOUNT_ERROR                =100022;
    const ERROR_MENU_EXISTS                 =100023;
    const ERROR_CHANNEL_EXISTS              =100024;
    const ERROR_CHANNEL_NOT_EXISTS         =100025;
    const ERROR_NOT_EXISTS                  =100026;
    const ERROR_EXISTS                      =100027;
    const ERROR_OPERATE_FAIL                =100028;
    const ERROR_ROOM_NOT_EXISTS             =100029;//房间不存在
    const ERROR_ROOM_HAS_RESERVE             =100030;//房间已预定
    const ERROR_ROOM_HAS_OCCUPANCY             =100031;//房间已入住
    const ERROR_CANCEL_NO_RIGHT             =100032;//房间已入住
    const ERROR_GUEST_SAVE_WRONG            =100033;//客户信息保存失败
    const ERROR_ORDER_CREATE_FAIL            =100034;//订单保存失败
    const ERROR_ROOM_CANNOT_REVERSE            =100035;//房间不能预定
    const ERROR_ROOM_CLOCK_DENY             =100036;//房间不允许钟点房
    const ERROR_ORDER_NOT_EXISTS             =100037;//订单不存在
    const ERROR_ORDER_HAS_CANCEL             =100038;//订单已取消
    const ERROR_ORDER_HAS_CHECK_OUT             =100039;//订单已结算
    const ERROR_ORDER_ROOM_UN_PLACE             =100040;//未定此房间
    const ERROR_ORDER_ROOM_CHANGE_FAIL             =100041;//订单房间状态修改失败
    const ERROR_ORDER_ROOM_PLACE_FAIL             =100042;//房间预订失败
    const ERROR_SET_BLACK_FAIL              =100043;
    const ERROR_DEL_MEMBER_FAIL              =100044;
    const ERROR_REMOVE_BLACK_FAIL              =100045;
    const ERROR_NOT_SET_MERCHANT                =100046;//未设置商户信息
    const ERROR_NO_GUEST_INFO           =100047;
    const ERROR_ROOM_HAS_PLACE           =100048;
    const ERROR_ROOM_DENY_CLOCK           =100049;
    const ERROR_OVER_CLOCK_MAX           =100050;
    const ERROR_ROOM_CLOCK_OVER_LIMIT           =100051;
    const ERROR_ROOM_STATUS_CHANGE_FAIL           =100052;
    const ERROR_OCCUPANCY_RECORD_ADD_FAIL           =100053;
    const ERROR_ROOM_PLACE_ADD_FAIL           =100054;
    const ERROR_ROOM_PLACE_UPDATE_FAIL           =100055;
    const ERROR_ORDER_PAY_RECORD_FAIL           =100056;
    const ERROR_ROOM_UNLOCK_FAIL           =100057;
    const ERROR_ORDER_BILL_INSERT_FAIL           =100058;
    const ERROR_ORDER_COST_CHANGE_FAIL           =100059;
    const ERROR_ORDER_ROOM_NO_TIME              =100060;
    const ERROR_ORDER_STATUS_CHANGE_FAIL              =100061;
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