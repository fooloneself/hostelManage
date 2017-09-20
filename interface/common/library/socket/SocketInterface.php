<?php
namespace common\library\socket;
interface SocketInterface{
    public function visit();
    public function connect();
    public function setSocketConfig(SocketConfigInterface $config);
    public function setSocketReader(SocketReaderInterface $reader);
    public function setSocketMessage(SocketMessageInterface $message);
    public function getSocketObject();
}