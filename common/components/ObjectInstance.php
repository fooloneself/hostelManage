<?php
namespace common\components;
class ObjectInstance{
    /**
     * 实例化对象
     * @param array $config
     * @return mixed
     */
    public static function instance(array $config){
        $class=$config['class'];
        unset($config['class']);
        list($reflect,$dependence)=self::getDependence($class);
        return $reflect->newInstanceArgs(self::getConstructorArg($config,$dependence));
    }

    /**
     * 获取类实例化所需的参数
     * @param array $config
     * @param array $dependence
     * @return array
     */
    protected static function getConstructorArg(array $config,array $dependence){
        $params=[];
        if(!empty($dependence)){
            foreach ($dependence as $name=>$value){
                $params[]=isset($config[$name])?$config[$name] : $value;
            }
        }
        return $params;
    }

    /**
     * 获取类实例化需要的参数名及默认值
     * @param $class
     * @return array
     */
    protected static function getDependence($class){
        $reflect=new \ReflectionClass($class);
        $constructor=$reflect->getConstructor();
        $dependence=[];
        if($constructor){
            $parameters=$constructor->getParameters();
            foreach ($parameters as $parameter){
                $dependence[$parameter->name]=$parameter->isDefaultValueAvailable()?$parameter->getDefaultValue():null;
            }
        }
        return [$reflect,$dependence];
    }
}