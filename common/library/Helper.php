<?php
namespace common\library;
class Helper{
    /**
     * 获取路由的hash值
     * @param $module
     * @param $controller
     * @param $action
     * @return string
     */
    public static function getHashOfRoute($module,$controller,$action){
        return md5(self::getRoute($module,$controller,$action));
    }

    /**
     * 组装路由
     * @param $module
     * @param $controller
     * @param $action
     * @return string
     */
    public static function getRoute($module,$controller,$action){
        return trim($module.'/'.$controller.'/'.$action,'/');
    }

    /**
     * xml转为数组
     * @param $simpleXmlElement
     * @return array
     */
    public static function xmlToArray($simpleXmlElement){
        $simpleXmlElement=(array)$simpleXmlElement;
        foreach($simpleXmlElement as $k=>$v){
            if($v instanceof \SimpleXMLElement ||is_array($v)){
                $simpleXmlElement[$k]=self::xmlToArray($v);
            }
        }
        return $simpleXmlElement;
    }
}