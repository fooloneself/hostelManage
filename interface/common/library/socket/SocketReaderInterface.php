<?php
namespace common\library\socket;
interface SocketReaderInterface{
    public function bindSocket(SocketInterface $socket);
    public function getResponse();
}