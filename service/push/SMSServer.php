<?php
namespace service\push;
use common\models\Config;

class SMSServer extends PushServer {
    const CONFIG_KEY='mj_no_push';
    public function check()
    {
        $value=Config::getValue(self::CONFIG_KEY);
        if($value===false){
            return true;
        }
        $schoolIds=explode(',', $value);
        $schoolId=$this->getSchoolId();
        return in_array($schoolId,$schoolIds)?false:true;
    }
}