<?php
namespace common\components\log;
use yii\db\ActiveRecord;

class DbTarget{
    //模型对象
    public $modelClass;
    protected $modelInstance;

    public function __construct($modelClass)
    {
        $this->modelClass=$modelClass;
    }

    public function log(array $logData=[]){
        if(!empty($logData)){
            $this->setLogData($logData);
        }
        $this->getModelInstance()->insert();
    }

    /**
     * 设置日志数据
     * @param array $logData
     * @return $this
     */
    public function setLogData(array $logData){
        $this->getModelInstance()->setAttributes($logData);
        return $this;
    }
    /**
     * 实例化模型
     * @return ActiveRecord
     */
    protected function getModelInstance(){
        if($this->modelInstance instanceof ActiveRecord){
            $this->modelInstance->setOldAttributes(null);
        }else{
            $className=$this->modelClass;
            $this->modelInstance= new $className();
        }
        return $this->modelInstance;
    }
}