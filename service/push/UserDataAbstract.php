<?php
namespace service\push;
abstract class UserDataAbstract{
    protected $server;
    public function bindServer(PushServer $server){
        $this->server=$server;
        $server->bindPushUser($this);
        return $this;
    }

    abstract public function getPushUsers();
}