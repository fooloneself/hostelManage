<?php
namespace common\components;
use service\admin\Admin;
use yii\base\Component;
class WebUser extends Component{
    private $_adminId;
    private $_token;
    private $_admin;
    public function init(){
        $this->_adminId=intval(\Yii::$app->session->get('uid'));
        $this->_token=\Yii::$app->session->get('token');
    }

    /**
     * 获取请求UID
     * @return mixed
     */
    public function getAdminId(){
        return $this->_adminId;
    }

    /**
     * 获取会话令牌
     * @return mixed
     */
    public function getToken(){
        return $this->_token;
    }

    /**
     * 获取管理员信息
     * @return static
     */
    public function getAdmin(){
        if($this->_admin===null)$this->_admin=Admin::byId($this->getAdminId());
        return $this->_admin;
    }
}

