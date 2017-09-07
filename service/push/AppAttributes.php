<?php
namespace service\push;

use common\models\Cookie;

class AppAttributes extends AttributesAbstract{
    const PUSH_TYPE=2;//推送类型：1短信2站内信
    const SEND_TYPE=3;//发送类型1运营平台2学校端3第三方
    public function getMessageAttributes()
    {
        return [
            'message_type'=>$this->messageType,
            'title'=>$this->title,
            'description'=>$this->desc,
            'push_type'=>self::PUSH_TYPE,
            'send_type'=>self::SEND_TYPE,
            'create_time'=>$_SERVER['REQUEST_TIME']
        ];
    }

    public function getPushAttributes($appUserId,$msgId)
    {
        list($pushId, $clientType) = Cookie::getAppInfo($appUserId);
        return [
            'message_id' => $msgId,
            'app_user_id' => $appUserId,
            'push_to' => $pushId,
            'app_type' => $clientType,
            'push_type' => self::PUSH_TYPE,
            'push_time' => $_SERVER['REQUEST_TIME']
        ];
    }
}