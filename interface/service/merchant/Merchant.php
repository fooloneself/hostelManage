<?php
namespace service\merchant;
use common\components\Server;
use common\models\MchModule;
use common\models\MerchantInfo;

class Merchant extends Server{
    public $id;
    protected $merchant;
    protected $merchantInfo;
    protected $modules;
    protected $moduleLabels;
    public function __construct($id)
    {
        $this->id=$id;
        $this->merchant=\common\models\Merchant::findOne(['id'=>$id]);
        if(empty($this->merchant))$this->merchant=new \common\models\Merchant();
        $this->merchantInfo=MerchantInfo::findOne(['mch_id'=>$id]);
        if(empty($this->merchantInfo))$this->merchantInfo=new merchantInfo();
    }

    /**
     * 获取商户信息字段
     * @return array
     */
    public function show(){
        return array_merge($this->merchant->getAttributes(),$this->merchantInfo->getAttributes());
    }

    /**
     * 刷新模块信息
     * @return $this
     */
    public function flushModule(){
        $this->modules=MchModule::allModuleOfMerchant($this->id);
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
        return intval($this->merchant->getAttribute('id'))>0;
    }
}