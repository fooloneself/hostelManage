<?php
namespace service\merchant;
use common\components\ErrorManager;
use common\components\Server;
use common\models\LinkageMenu;
use common\models\MchModule;

class Merchant extends Server{
    protected $merchant;
    protected $modules;
    protected $moduleLabels;
    public function __construct(\common\models\Merchant $merchant)
    {
        $this->merchant=$merchant;
    }

    /**
     * 获取商户信息字段
     * @return array
     */
    public function show(){
        return $this->merchant->getAttributes();
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
    public function register(){
        $city=LinkageMenu::findOne(['type'=>LinkageMenu::TYPE_REGION,'id'=>$this->merchant->getAttribute('city')]);
        if(empty($city)){
            $this->setError(ErrorManager::ERROR_PARAM_WRONG,'未找到所属地区');
            return false;
        }else if(!in_array($this->merchant->type,\common\models\Merchant::$types,true)){
            $this->setError(ErrorManager::ERROR_PARAM_WRONG,'类型错误');
            return false;
        }
        $this->merchant->create_time=time();
        if($this->merchant->insert()){
            return true;
        }else{
            $this->setError(ErrorManager::ERROR_INSERT_FAIL,json_encode($this->merchant->getErrors()));
            return false;
        }
    }
}