<?php
namespace common\components;
class Server{
    private $_error;
    public static function className(){
        return get_called_class();
    }

    public function setError($status,$msg=''){
        if($status instanceof Error){
            $this->_error=$status;
        }else{
            $this->_error=new Error($status,$msg);
        }
        return $this;
    }

    public function getError(){
        return $this->_error;
    }
}