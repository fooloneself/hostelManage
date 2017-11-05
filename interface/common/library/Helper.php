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

    /**
     * 校验账号
     * @param $userName
     * @return bool
     */
    public static function checkUsername($userName){
        return preg_match('/^\w{6,18}$/',$userName)==1? true: false;
    }

    /**
     * 校验密码
     * @param $password
     * @return bool
     */
    public static function checkPwd($password){
        return preg_match('/(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{6,16}$/',$password)==1? true: false;
    }

    /**
     * 加密登录密码
     * @param $pwd
     * @return string
     */
    public static function encryptPwd($pwd){
        return md5($pwd);
    }

    /**
     * 生产订单号
     * @param $mchId
     * @return string
     */
    public static function makeOrderNum($mchId){
        return 'A'.str_pad(base_convert($mchId,10,16),STR_PAD_LEFT,'0',6).date('YmdHis').rand(10,99);
    }
}