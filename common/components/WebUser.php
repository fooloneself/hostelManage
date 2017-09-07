<?php
namespace common\components;
use common\models\AppUser;
use common\models\AppUserRelation;
use common\models\Cookie;
use service\user\RelationPersonServer;
use service\user\SessionServer;
use service\user\UserServer;
use yii\base\Component;
class WebUser extends Component{
    private $_service=[];
    private $_userId;
    private $_token;
    public function init(){
        $helper=\Yii::$app->requestHelper;
        $this->_userId=$helper->getUid();
        $this->_token=$helper->getToken();
    }

    /**
     * 获取请求UID
     * @return mixed
     */
    public function getUserId(){
        return $this->_userId;
    }

    /**
     * 获取会话令牌
     * @return mixed
     */
    public function getToken(){
        return $this->_token;
    }
    /**
     * 实例化服务类
     * @param $name
     * @return mixed
     */
    protected function instanceServer($config){
        $className=$config['class'];
        if(!isset($this->_service[$className])){
            $this->_service[$className]=ObjectInstance::instance($config);
        }
        return $this->_service[$className];
    }

    /**
     * 获取账号管理类
     * @return mixed
     */
    public function getAccount(){
        return $this->instanceServer([
            'class'=>UserServer::className(),
            'userId'=>$this->_userId
        ]);
    }

    /**
     * 获取会话管理类
     * @return mixed
     */
    public function getSession(){
        return $this->instanceServer([
            'class'=>SessionServer::className(),
            'userId'=>$this->_userId,
            'token'=>$this->_token
        ]);
    }

    /**
     * 获取账号关联的校园人员
     * @return mixed
     */
    public function getPerson(){
        return $this->instanceServer([
            'class'=>RelationPersonServer::className(),
            'userId'=>$this->_userId
        ]);
    }
}

