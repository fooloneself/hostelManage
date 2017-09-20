<?php
namespace common\components\log;
use common\components\ObjectInstance;
use yii\base\Component;

class Log extends Component{
    //日志记录器配置集合
    public $targets=[];
    //默认日志记录器
    public $defaultTarget='common\components\log\FileTarget';
    //日志记录器对象表
    protected $_targetsInstance=[];

    /**
     * 获取日志记录器的对象
     * @param null $name
     * @return mixed
     */
    public function getLogger($name=null){
        $config=$this->getTarget($name);
        $class=$config['class'];
        if(!isset($this->_targetsInstance[$class])){
            $this->_targetsInstance[$class]=ObjectInstance::instance($config);
        }
        return $this->_targetsInstance[$class];
    }
    /**
     * 获取日志记录器配置
     * @param $name
     * @return array|mixed|string
     */
    protected function getTarget($name){
        if(!is_null($name) && isset($this->targets[$name])){
            $config=$this->targets[$name];
            if(is_string($config)){
                $config=[
                    'class'=>$config
                ];
            }else if(!isset($config['class'])){
                $config['class']=$this->defaultTarget;
            }
            return $config;
        }else{
            return ['class'=>$this->defaultTarget];
        }
    }
}
