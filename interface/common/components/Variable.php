<?php
namespace common\components;
class Variable{
    const TYPE_INT='int';
    const TYPE_FLOAT='float';
    const TYPE_STRING='string';
    const TYPE_ARRAY='array';
    const TYPE_OBJECT='object';
    private static $_instance;
    private $_value;
    private function __construct($value)
    {
        $this->setValue($value);
    }
    public function setValue($value){
        $this->_value=$value;
        return $this;
    }
    public static function instance($value=null){
        if(self::$_instance===null){
            self::$_instance=new static($value);
        }
        return self::$_instance;
    }

    public function isInt(){
        return is_int($this->_value);
    }

    public function isString(){
        return is_string($this->_value);
    }

    public function isArray(){
        return is_array($this->_value);
    }

    public function isFloat(){
        return is_float($this->_value);
    }

    public function isObject(){
        return is_object($this->_value);
    }

    public function toString(){
        return (string)$this->_value;
    }

    public function toInt(){
        return (int)$this->_value;
    }

    public function toFloat(){
        return floatval($this->_value);
    }

    public function toArray(){
        return (array)$this->_value;
    }

    public function is($type){
        $method='is'.ucfirst($type);
        return $this->$method();
    }

    public function to($type){
        $method='to'.ucfirst($type);
        return $this->$method();
    }
}