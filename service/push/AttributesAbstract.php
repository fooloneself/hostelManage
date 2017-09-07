<?php
namespace service\push;
abstract class AttributesAbstract{
    const MESSAGE_TYPE_SAFE_ATTENDANCE=6;//平安考勤
    const MESSAGE_TYPE_SHORT_MSG=10;//短信推送
    public $title;
    public $desc;
    public $messageType;
    protected $server;
    abstract public function getMessageAttributes();
    abstract public function getPushAttributes($appUserId,$msgId);
    public function __construct($title,$desc)
    {
        $this->title=$title;
        $this->desc=$desc;
    }

    public function bindPushServer(PushServer $server)
    {
        $this->server=$server;
        $server->bindAttribute($this);
    }
}