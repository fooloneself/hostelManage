<?php
namespace service\icRecord;
use common\components\BaseServer;
use common\library\socket\Socket;
use common\library\socket\SocketConfig;
use common\library\socket\SocketMessage;
use common\library\socket\SocketReader;
use common\models\PartnerPortLog;

class  ICRecordServer extends BaseServer{
    const STATUS_SUCCESS='0000';
    //现金圈存
    const CODE_CASH_RECHARGE=3004;
    //消费记录
    const CODE_CONSUMPTION=3005;
    //客户号消费记录
    const CODE_CUSTOM_NUM_CONSUMPTION=3011;
    //客户号充值记录
    const CODE_CUSTOM_NUM_RECHARGE=3012;
    //充值总汇
    const CODE_RECHARGE_CONFLUENCE=3015;
    //消费总汇
    const CODE_CONSUMPTION_CONFLUENCE=3016;
    //卡工本费
    const CODE_CARD_FEE=3018;
    protected $code;
    protected $params=[];
    /**
     * IC系统错误码 与 开放平台错误码对照表
     * @var array
     */
    public $errorComparisonTable=[];
    /**
     * 获取错误对象
     * @param $errorCode
     * @param string $errMsg
     * @return mixed
     */
    public function addError($errorCode,$errMsg=''){
        if(isset($this->errorComparisonTable[$errorCode])){
            $errorCode=$this->errorComparisonTable[$errorCode];
        }
        $error=\Yii::$app->errorManager->generateError();
        $error->status=$errorCode;
        $error->msg=$errMsg;
        return $error;
    }

    /**
     * 设置交易码
     * @param $code
     * @return $this
     */
    public function setCode($code){
        $this->code=$code;
        return $this;
    }

    /**
     * 设置请求参数
     * @param array $params
     * @return $this
     */
    public function setParams(array $params=[]){
        $this->params=$params;
        return $this;
    }

    /**
     * 获取请求结果
     * @return bool
     */
    public function getResponse(){
        $res=$this->socketVisit();
        if($res===false){
            return false;
        }
        if(isset($res['header']['ErrCode']) && $res['header']['ErrCode']==self::STATUS_SUCCESS){
            $this->log($res['body']);
            return $res['body'];
        }else{
            $this->addError($res['header']['ErrCode'],$res['header']['ErrMsg']);
            $this->log($res,0);
            return false;
        }
    }

    /**
     * 访问ic系统网关
     * @return bool
     */
    protected function socketVisit(){
        $config=new SocketConfig();
        $config->addOption(SOL_SOCKET, SO_SNDTIMEO, array("sec" => 3, "usec" => 0));
        $socketMessage=self::instanceSocketMessage($this->code,$this->params);
        $socketReader=new SocketReader();
        $socket=new Socket();
        $socket->setSocketConfig($config);
        $socket->setSocketMessage($socketMessage);
        $socket->setSocketReader($socketReader);
        $socket->ip=$this->getSocketConfig('IC_API_IP');
        $socket->port=$this->getSocketConfig('IC_API_PORT');
        if($socket->connect()){
            if(($res=$socket->visit())===false){
                $this->log('结果读取失败',0);
                $error=\Yii::$app->errorManager->generateError();
                $error->status=ErrorManager::ERROR_THIRD_GATEWAY_ERROR;
                return false;
            }else{
                return $res;
            }
        }else{
            $error=\Yii::$app->errorManager->generateError();
            $error->status=ErrorManager::ERROR_THIRD_GATEWAY_NU_CONNECT;
            $this->log('连接失败',0);
            return false;
        }
    }

    /**
     * 实例化一个socket服务
     * @param $code
     * @param $params
     * @return SocketMessage
     */
    public function instanceSocketMessage($code,$params){
        $socketMessage=new SocketMessage();
        $socketMessage->setBodyParams($params);
        $socketMessage->setCode($code);
        $socketMessage->icApiUserName=self::getSocketConfig('IC_API_USERNAME');
        $socketMessage->icApiPwd=self::getSocketConfig('IC_API_PWD');
        $socketMessage->icApiTermId=self::getSocketConfig('IC_API_TERMID');
        $socketMessage->macPort=self::getSocketConfig('MAC_PORT');
        $socketMessage->macIp=self::getSocketConfig('MAC_IP');
        $socketMessage->defaultTrBranch=self::getSocketConfig('IC_API_TRBRANCH');
        return $socketMessage;
    }

    /**
     * 获取ic系统网关配置
     * @param null $name
     * @return mixed
     */
    public function getSocketConfig($name=null){
        $config=\Yii::$app->params['icSocket'];
        if(empty($name)){
            return $config;
        }
        return $config[$name];
    }

    /**
     * 请求日志
     * @param $request
     * @param $response
     * @param int $status
     */
    public function log($response, $status = 1){
        $response=json_encode($response);
        $ip=$this->getSocketConfig('IC_API_IP');
        $port=$this->getSocketConfig('IC_API_PORT');
        $message ='code：'.$this->code."\n";
        $message.='address:'.$ip.':'.$port."\n";
        $message.='info: status['.($status==1?'成功':'失败').'] date['.date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME'])."]\n";
        $message.='request:'.json_encode($this->params)."\n";
        $message.='response:'.$response."\n";
        \Yii::$app->fileLog->getLogger('icRecord')->log($message);
    }
}