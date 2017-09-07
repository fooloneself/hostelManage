<?php
namespace common\components;
class Validate{
    /**
     * 校验登录密码格式
     * @param $pwd
     * @return int
     */
    public static function checkPwd($pwd){
        return preg_match('/^(?![\\d]+$)(?![a-zA-Z]+$)(?![^\\da-zA-Z]+$).{6,12}$/', $pwd);
    }

    /**
     * 校验登录用户名格式
     * @param $userName
     * @return int
     */
    public static function checkUserName($userName){
        return preg_match("/^1[3|4|5|7|8][0-9]{9}$/", $userName);
    }
}