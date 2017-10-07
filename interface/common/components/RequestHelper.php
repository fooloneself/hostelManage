<?php
namespace common\components;
use yii\base\Component;

class RequestHelper extends Component{
    public $subUserId='uid';
    public $subToken='token';
    public $subVersion='version';
    private $_requestParams;
    public function init(){
        parent::init();
        $param=\Yii::$app->request->getRawBody();
        $this->_requestParams=json_decode($param,true);
        $this->_requestParams=[
            'uid'=>3,
            'token'=>'86241E0E-5809-6E9F-D11E-F627D7611917',
            'city'=>5,
            'name'=>'费率并',
            'address'=>'成都少陵路218号',
            'type'=>1,
            'premises'=>1,
            'number'=>12,
            'floor'=>7
        ];
        if(empty($this->_requestParams)){
            $this->_requestParams=[];
        }
    }

    /**
     * 获取post参数
     * @param null $name
     * @param null $default
     * @param null $type
     * @return bool|mixed|null
     */
    public function post($name=null,$default=null,$type=null){
        if($name){
            $param= isset($this->_requestParams[$name])?$this->_requestParams[$name]:$default;
            if(isset($type) && isset($params) && Variable::instance()->setValue($param)->is($type)===false){
                \Yii::$app->responseHelper->error(ErrorManager::ERROR_PARAM_TYPE_WRONG)->response(true);
            }
            return $param;
        }else{
            return $this->_requestParams;
        }
    }

    /**
     * 获取用户ID
     * @return bool|mixed|null
     */
    public function getUid(){
        return $this->post($this->subUserId);
    }

    /**
     * 获取会话token
     * @return bool|mixed|null
     */
    public function getToken(){
        return $this->post($this->subToken);
    }

    /**
     * 获取APP版本号
     * @return bool|mixed|null
     */
    public function getVersion(){
        return $this->post($this->subVersion);
    }

    /**
     * 获取终端类型
     * @return int
     */
    public function getClientType(){
        static $clientType;
        if($clientType===null){
            $clientType=$_SERVER['HTTP_USER_AGENT'] == 'iPhone'?2:1;
        }
        return $clientType;
    }
}