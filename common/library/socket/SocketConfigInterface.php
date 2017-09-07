<?php
namespace common\library\socket;
interface SocketConfigInterface{
    public function setDomain($domain);
    public function getDomain();
    public function setType($type);
    public function getType();
    public function setProtocol($protocol);
    public function getProtocol();
    public function getOptions();
}