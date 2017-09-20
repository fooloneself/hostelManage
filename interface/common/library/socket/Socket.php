<?php
namespace common\library\socket;
class Socket implements SocketInterface {
    public $ip;
    public $port;
    protected $socketConfig;
    protected $socketMessage;
    protected $socketReader;
    protected $socketObject;
    protected function createSocket(){
        $client = socket_create($this->socketConfig->getDomain(), $this->socketConfig->getType(), $this->socketConfig->getProtocol());
        $options=$this->socketConfig->getOptions();
        foreach ($options as $option){
            $reflect=new \ReflectionFunction('socket_set_option');
            array_unshift($option,$client);
            $reflect->invokeArgs($option);
        }
        return $client;
    }

    public function getSocketObject(){
        if($this->socketObject==null){
            $this->socketObject=$this->createSocket();
        }
        return $this->socketObject;
    }

    public function connect()
    {
        return socket_connect($this->getSocketObject(),$this->ip,$this->port);
    }

    public function setSocketConfig(SocketConfigInterface $config)
    {
        $this->socketConfig=$config;
        return $this;
    }

    public function setSocketMessage(SocketMessageInterface $message)
    {
        $this->socketMessage=$message;
        return $this;
    }

    public function setSocketReader(SocketReaderInterface $reader)
    {
        $this->socketReader=$reader;
        $reader->bindSocket($this);
        return $this;
    }

    public function visit()
    {
        socket_write($this->getSocketObject(),$this->socketMessage->getMessage());
        $res=$this->socketReader->getResponse();
        socket_close($this->getSocketObject());
        return $res;
    }
}

