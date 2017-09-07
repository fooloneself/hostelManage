<?php
namespace common\components;
use yii\base\Component;
use common\components\Error;
class ResponseHelper extends Component{
    //返回的数据信息
    private $_data=[];
    //返回的状态码
    private $_status=ErrorManager::STATUS_SUCCESS;
    //操作结果
    private $_result=1;
    //结果信息
    private $_msg='';

    /**
     * 设置返回的数据信息
     * @param array $data
     * @return $this
     */
    public function setResponseData(array $data){
        $this->_data=$data;
        return $this;
    }

    /**
     * 设置错误返回
     * @param $status
     * @param string $msg
     * @return $this
     */
    public function error($status,$msg=''){
        $error=\Yii::$app->errorManager->generateError($status,$msg);
        $this->setErrorObj($error);
        return $this;
    }

    /**
     * 设置错误返回信息 以common\components\Error传递
     * @param \common\components\Error $error
     * @return ResponseHelper
     */

    public function setErrorObj(Error $error){
        $this->_status=$error->getStatus();
        $this->_msg=$error->getMsg();
        $this->_result=0;
        return $this;
    }
    /**
     * 设置成功信息
     * @param array $data
     * @param string $msg
     * @return $this
     */
    public function success(array $data=[],$msg=''){
        $this->_status=ErrorManager::STATUS_SUCCESS;
        $this->_msg=$msg;
        $this->setData($data);
        $this->_result=1;
        return $this;
    }

    /**
     * 设置返回的数据
     * @param array $data
     * @return $this
     */
    public function setData(array $data){
        $this->_data=$data;
        return $this;
    }
    /**
     * 构造返回的数据结构
     * @return array
     */
    protected function constructData(){
        $res=[
            'code'=>$this->_status,
            'msg'=>$this->_msg,
            'success'=>$this->_result
        ];
        if($this->_status==ErrorManager::STATUS_SUCCESS){
            $res['results']=$this->_data;
        }
        return $res;
    }

    /**
     * 设置Yii::$app->getResponse()->data
     * @param bool $end
     * @return \yii\console\Response|\yii\web\Response
     */
    public function response($end=false){
        $response=\Yii::$app->getResponse();
        $response->data=json_encode($this->constructData());
        if($end===true){
            $this->end();
        }else{
            return $response;
        }
    }

    /**
     * 结束本次访问
     */
    public function end(){
        \Yii::$app->end();
    }

    /**
     * 获取相应结果
     * @param bool $jsonDecode
     * @return mixed
     */
    public function getResponseResult($jsonDecode=true){
        $data=\Yii::$app->getResponse()->data;
        if($jsonDecode===true){
            return json_decode($data);
        }else{
            return $data;
        }
    }
}