<?php
namespace common\components;
use service\admin\Privilege;

class Module extends \yii\base\Module{
    public $moduleName;
    protected $privilege=[];
    /**
     * 校验权限
     * @param Privilege $privilege
     * @param $action
     * @return bool
     */
    protected function checkPrivilege(Privilege $privilege,$action){
        if(!$this->needCheckPrivilege($action)){
            return true;
        }else{
            return $privilege->hasPrivilege($this->needPrivilege($action));
        }
    }

    /**
     * 是否需要校验权限
     * @param $action
     * @return bool
     */
    protected function needCheckPrivilege($action){
        $actionId=$action->id;
        $controllerId=$action->controller->id;
        if(isset($this->privilege[$controllerId]) && isset($this->privilege[$controllerId][$actionId])){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 接口需要权限
     * @param $action
     * @return bool
     */
    protected function needPrivilege($action){
        $actionId=$action->id;
        $controllerId=$action->controller->id;
        $moduleId=$this->id;
        return self::makePrivilegeCode($moduleId,$controllerId,$actionId);
    }

    /**
     * 生成权限code
     * @param $moduleId
     * @param $controllerId
     * @param $actionId
     * @return string
     */
    protected static function makePrivilegeCode($moduleId,$controllerId,$actionId){
        return md5(implode('/',[$moduleId,$controllerId,$actionId]));
    }

    /**
     * 所有权限(分组)
     * @return array
     */
    public function allPrivilegeWithGroup(){
        $res=[];
        foreach ($this->privilege as $controllerId=>$actions){
            $groupName=$actions['_label'];
            unset($actions['_label']);
            $privileges=[];
            foreach ($actions as $actionId=>$label){
                $privileges[self::makePrivilegeCode($this->id,$controllerId,$actionId)]=$label;
            }
            $res[]=[
                'label'=>$groupName,
                'privileges'=>$privileges,
            ];
        }
        return $res;
    }

    /**
     * 所有权限
     * @return array
     */
    public function allPrivilege(){
        $res=[];
        foreach ($this->privilege as $controllerId=>$actions){
            unset($actions['_label']);
            foreach ($actions as $actionId=>$label){
                $res[]=self::makePrivilegeCode($this->id,$controllerId,$actionId);
            }
        }
        return $res;
    }

    /**
     * 获取所有模块及注解
     * @return array
     */
    public function allModuleLabels(){
        $modules=[];
        foreach ($this->privilege as $controllerId=>$actions){
            $modules[$controllerId]=isset($actions['_label'])?$actions['_label']:'';
        }
        return $modules;
    }

    /**
     * 获取所有模块
     * @return array
     */
    public function allModule(){
        return array_keys($this->privilege);
    }

    /**
     * 获取模块名称
     * @param $moduleCode
     * @return string
     */
    public function moduleLabel($moduleCode){
        if(isset($this->privilege[$moduleCode])){
            return isset($this->privilege[$moduleCode]['_label'])?$this->privilege[$moduleCode]['_label']:'';
        }else{
            return false;
        }
    }

    /**
     * 指定模块下的所有权限
     * @param array $modules
     * @return array
     */
    public function allPrivilegeOfModules(array $modules){
        $privileges=[];
        foreach ($this->privilege as $controllerId=>$actions){
            if(in_array($controllerId,$modules)){
                unset($actions['_label']);
                foreach ($actions as $actionId=>$label){
                    $privileges[]=self::makePrivilegeCode($this->id,$controllerId,$actionId);
                }
            }
        }
        return $privileges;
    }
}