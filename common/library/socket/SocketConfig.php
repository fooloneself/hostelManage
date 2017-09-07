<?php
namespace common\library\socket;
class SocketConfig implements SocketConfigInterface {
    protected $options=[];
    protected $domain   =AF_INET;
    protected $type     =SOCK_STREAM;
    protected $protocol =SOL_TCP;
    public function setDomain($domain)
    {
        $this->domain=$domain;
        return $this;
    }
    public function getDomain()
    {
        return $this->domain;
    }

    public function setType($type)
    {
        $this->type=$type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setProtocol($protocol)
    {
        $this->protocol=$protocol;
        return $this;
    }

    public function getProtocol()
    {
        return $this->protocol;
    }
    public function addOption()
    {
        $this->options[]=func_get_args();
        return $this;
    }
    public function setOptions(array $options){
        $this->options=$options;
        return $this;
    }
    public function getOptions()
    {
        return $this->options;
    }
}