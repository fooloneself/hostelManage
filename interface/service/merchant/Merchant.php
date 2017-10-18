<?php
namespace service\merchant;
use common\components\ErrorManager;
use common\components\Server;
use common\models\LinkageMenu;
use common\models\MchModule;
use common\models\Premises;

class Merchant extends Server{
    protected $merchant;
    protected $modules;
    protected $moduleLabels;
    protected $premise;

    public function __construct(\common\models\Merchant $merchant)
    {
        $this->merchant=$merchant;
    }

    /**
     * 设置属性
     * @param $name
     * @param $value
     * @return $this
     */
    public function set($name,$value){
        $this->merchant->setAttribute($name,$value);
        return $this;
    }

    /**
     * 获取属性值
     * @param $name
     * @return mixed
     */
    public function get($name){
        return $this->merchant->getAttribute($name);
    }

    /**
     * 魔术方法-设置属性值
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->set($name,$value);
    }

    /**
     * 魔术方法-获取属性值
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * 获取商户信息字段
     * @return array
     */
    public function show(){
        return $this->merchant->getAttributes();
    }

    /**
     * 设置多属性在
     * @param $attrs
     */
    public function put($attrs){
        $this->merchant->setAttributes($attrs);
        return $this;
    }
    /**
     * 刷新模块信息
     * @return $this
     */
    public function flushModule(){
        $this->modules=MchModule::allModuleOfMerchant($this->getId());
        $labels=[];
        foreach ($this->modules as $module){
            $label=\Yii::$app->getModule('merchant')->moduleLabel($module);
            if($label===false)continue;
            $labels[$module]=$label;
        }
        $this->moduleLabels=$labels;
        return $this;
    }

    /**
     * 获取商户所有的模块
     * @return mixed
     */
    public function allModule(){
        if($this->modules===null){
            $this->flushModule();
        }
        return $this->modules;
    }

    /**
     * 获取模块及注解
     * @return null
     */
    public function allModuleLabel(){
        if($this->moduleLabels===null){
            $this->flushModule();
        }
        return $this->moduleLabels;
    }

    /**
     * 商户是否存在
     * @return bool
     */
    public function isExists(){
        return $this->getId()>0;
    }

    /**
     * 获取商户id
     * @return int
     */
    public function getId(){
        return intval($this->merchant->getAttribute('id'));
    }
    /**
     * 通过id获取商户信息
     * @param $id
     * @return static
     */
    public static function byId($id){
        $model=\common\models\Merchant::findOne(['id'=>$id]);
        if(empty($model))$model=new \common\models\Merchant();
        return new static($model);
    }

    /**
     * 注册商户
     * @return bool
     */
    public function save(){
        $this->merchant->create_time=time();
        if(!$this->merchant->save()){
            $this->setError(ErrorManager::ERROR_OPERATE_FAIL,'商户设置失败');
            return false;
        }
        $premise=$this->getPremise();
        if(!$premise){
            $premise=new Premises();
            $premise->mch_id=$this->merchant->id;
            $premise->create_time=time();
            $this->premise=$premise;
        }
        $premise->city=0;
        $premise->address=$this->merchant->address;
        if($premise->save()){
            return true;
        }else{
            $this->setError(ErrorManager::ERROR_OPERATE_FAIL,'商户设置失败');
            return false;
        }
    }

    /**
     * 获取经营场所
     * @return static
     */
    public function getPremise(){
        if($this->premise===null){
            $this->premise=Premises::findOne(['mch_id'=>$this->getId()]);
            if(empty($this->premise))$this->premise=false;
        }
        return $this->premise;
    }
}