<?php
namespace app\commands\controllers;
use common\library\Helper;
use common\models\FuncInterface;
use yii\console\Controller;
use yii\db\ActiveRecord;

/**
 * 主类
 * Class Entrance
 * @package app\commands\controllers
 */
class Entrance extends Controller {
    public $module;
    public $controller;
    public $action;
    public $deny;
    public $allow;
    public function options($actionId){
        return ['module','controller','action','deny','allow'];
    }
    /**
     * 注册所有的配置的模块接口
     */
    public function actionRegister(){
        $sprinter=new RegisterSprint();
        $modelFiller=new RegisterInterfaceModelFiller();
        $interfaceOperator=new InterfaceRegister();
        $interfaceOperator->bindSprinter($sprinter);
        $interfaceOperator->setModelClassName(FuncInterface::className());
        $interfaceOperator->bindModelFiller($modelFiller);
        $docParser=new DocParser();
        $actionIterator=new ActionIterator();
        $actionIterator->setDocParser($docParser);
        $actionIterator->bindInterfaceOperator($interfaceOperator);
        $controllerIterator=new ControllerIterator();
        $controllerIterator->bindActionIterator($actionIterator);
        $moduleIterator=new ModuleIterator();
        $moduleIterator->bindControllerIterator($controllerIterator);
        $moduleIterator->traverse();
    }

    public function actionUpdate(){
        $sprinter=new RegisterSprint();
        $modelFiller=new RegisterInterfaceModelFiller();
        $interfaceOperator=new InterfaceRegister();
        $interfaceOperator->bindSprinter($sprinter);
        $interfaceOperator->setModelClassName(FuncInterface::className());
        $interfaceOperator->bindModelFiller($modelFiller);
        $actionIterator=new ActionIterator();
        $actionIterator->bindInterfaceOperator($interfaceOperator);
        $controllerIterator=new ControllerIterator();
        $controllerIterator->bindActionIterator($actionIterator);
        $moduleIterator=new ModuleIterator();
        $moduleIterator->bindControllerIterator($controllerIterator);
        $moduleIterator->traverse();
    }
}
class StringHelper{
    public static function formatYiiRoute($route){
        $len=strlen($route);
        $str='';
        for($i=0;$i<$len;$i++){
            $ord=ord($route{$i});
            if($ord>=65 && $ord<=90){
                $str.='-'.chr($ord+32);
            }else{
                $str.=$route{$i};
            }
        }
        return $str;
    }
}
class Object{
    public static function className(){
        return get_called_class();
    }
}
class DocParser{
    public $prefix='#';
    public function parse($doc){
        $str=explode("\r",$doc);
        $res=[];
        $prefix='* '.$this->prefix;
        $length=strlen($prefix);
        foreach ($str as $item){
            $item=ltrim($item);
            if(strpos($item,$prefix)===0){
                $item=trim(substr($item,$length));
                $item=explode(' ',$item);
                $res[$item[0]]=end($item);
            }
        }
        return $res;
    }
}
abstract class SprintAbstract extends Object{
    public function sprint(){
        $info=$this->getInfo();
        echo str_replace(array_keys($info),array_values($info),$this->getMsgTemp()),"\n";
    }
    abstract public function getInfo();
    abstract public function getMsgTemp();
}

class RegisterSprint extends SprintAbstract{
    public $route;
    public $hash;
    public $result;

    public function getInfo(){
        return [
            '__INTERFACE__'=>$this->route,
            '__HASH__'=>$this->hash,
            '__RESULT__'=>$this->result,
        ];
    }

    public function getMsgTemp(){
        return 'interface[__INTERFACE__][__HASH__] __RESULT__';
    }
}

class UpdateSprint extends SprintAbstract{
    public $route;
    public $result;
    public function getMsgTemp(){
        return 'interface[__INTERFACE__] __RESULT__';
    }
    public function getInfo(){
        return [
            '__INTERFACE__'=>$this->route,
            '__RESULT__'=>$this->result
        ];
    }
}
abstract class ModelFillerAbstract extends Object {
    protected $interfaceOperator;
    public function bindInterfaceOperator(InterfaceOperateAbstract $operator){
        $this->interfaceOperator=$operator;
        return $this;
    }
    abstract public function fill($model);
}

class RegisterInterfaceModelFiller extends ModelFillerAbstract
{
    public function fill($model){
        $hash=$this->interfaceOperator->getRouteHash();
        $route=$this->interfaceOperator->getRoute();
        $model->hash=$hash;
        $model->route=$route;
        $model->name=$this->interfaceOperator->name;
        $model->description=$this->interfaceOperator->desc;
        $model->module_code=$this->interfaceOperator->moduleCode;
        $model->status=$this->interfaceOperator->status;
        $model->is_new=1;
        $model->register_time=$_SERVER['REQUEST_TIME'];
    }
}
class UpdateInterfaceModelFiller extends ModelFillerAbstract{
    public function fill($model){
        if($model){
            $model->load($this->interfaceOperator->getChangeClass()->getAttributes());
        }
    }
}
abstract class InterfaceOperateAbstract extends Object{
    public $module;
    public $controller;
    public $action;
    public $name;
    public $desc;
    public $moduleCode;
    public $status;

    protected $modelFiller;
    protected $sprinter;
    protected $modelClassName;
    protected $model;
    public function bindModelFiller(ModelFillerAbstract $filler){
        $this->modelFiller=$filler;
        $filler->bindInterfaceOperator($this);
        return $this;
    }

    public function bindSprinter(SprintAbstract $sprinter){
        $this->sprinter=$sprinter;
        return $this;
    }

    public function getRoute(){
        return StringHelper::formatYiiRoute(Helper::getRoute($this->module,$this->controller,$this->action));
    }

    public function getRouteHash(){
        return md5($this->getRoute());
    }

    protected function getModel(){
        if($this->model==null){
            $className=$this->modelClassName;
            $this->model=new $className();
        }
        return $this->model;
    }

    public function setModelClassName($modelClassName){
        $this->modelClassName=$modelClassName;
        return $this;
    }

    public function fillModel(){
        $model=$this->getModel();
        $this->modelFiller->fill($model);
        return $model;
    }
    public function handle(){
        $model=$this->fillModel();
        $this->execute($model);
        $this->sprinter->sprint();
    }
    abstract public function execute(ActiveRecord $model);
}
class InterfaceRegister extends InterfaceOperateAbstract {

    public function execute(ActiveRecord $model){
        $this->sprinter->route=$model->route;
        $this->sprinter->hash=$model->hash;
        if($this->hasExit($model->hash)){
            $this->sprinter->result='has register';
        }else{
            $model->setOldAttributes(null);
            if($model->insert(false,['route','hash','name','description','module_code','register_time','is_new','status'])){
                $this->sprinter->result='register success';
            }else{
                $this->sprinter->result='register fail';
            }
        }
    }
    protected function hasExit($hash){
        $modelClass=$this->modelClassName;
        $count=$modelClass::find()->where(['hash'=>$hash])->count();
        if($count>0){
            return true;
        }else{
            return false;
        }
    }
}

class InterfaceUpdate extends InterfaceOperateAbstract
{
    protected $changeClass;
    protected function getModel(){
        $route=$this->getRoute();
        if(!$this->model || $this->model->route!=$route){
            $className=$this->modelClassName;
            $this->model=$className::find()->where(['route'=>$this->getRoute()])->one();
        }
        return $this->model;
    }

    public function bindChangeClass(InterfaceChangeAbstract $changer){
        $this->changeClass=$changer;
        $changer->setUpdater($this);
        return $this;
    }

    public function getChangeClass(){
        return $this->changeClass;
    }
    public function execute(ActiveRecord $model){
        if($this->model==null){
            $this->sprinter->result=' not find';
        }else{
            if($model->update()){
                $this->sprinter->result=' update success';
            }else{
                $this->sprinter->result=' update fail';
            }
        }
    }
}

abstract class InterfaceChangeAbstract extends Object {
    protected $modelFiller;
    public function __construct(ModelFillerAbstract $filler=null){
        if($filler){
            $this->bindModelFiller($filler);
        }
    }

    public function bindModelFiller(ModelFillerAbstract $filler){
        $this->modelFiller=$filler;
        return $this;
    }

    abstract public function getAttributes();
}
abstract class Iterator extends Object{
    protected $list=[];
    abstract public function generateList();
    abstract public function handle($item);
    public function traverse(){
        $res=$this->generateList();
        if(is_array($res)){
            $this->list=$res;
        }
        foreach ($this->list as $item){
            $this->handle($item);
        }
    }
}
class ModuleIterator extends Iterator{
    protected $limit;
    protected $controllerIterator;
    public function bindControllerIterator(ControllerIterator $controllerIterator){
        $this->controllerIterator=$controllerIterator;
        $controllerIterator->bindModuleIterator($this);
        return $this;
    }

    public function setLimit($limit){
        $this->limit=$limit;
        return $this;
    }
    public function generateList(){
        $modules=\Yii::$app->getModules();
        $list=[];
        foreach ($modules as $moduleName=>$moduleClass){
            $list[]=[$moduleName,$moduleClass];
        }
        return $list;
    }

    public function getModulePathAndNamespace($moduleClass){
        $class = new \ReflectionClass($moduleClass);
        $path=dirname($class->getFileName());
        $namespace=$class->getNamespaceName();
        return [$path,$namespace];
    }
    public function handle($item){
        list($moduleName,$moduleClassName)=$item;
        $this->controllerIterator->setBelongToModule($moduleName)->setModuleClassName($moduleClassName)->traverse();
    }
}

class ControllerIterator extends Iterator{
    protected $belongToModule;
    protected $moduleClassName;
    protected $moduleIterator;
    protected $actionIterator;
    public function bindModuleIterator(ModuleIterator $iterator){
        $this->moduleIterator=$iterator;
        return $this;
    }

    public function setModuleClassName($moduleClassName){
        $this->moduleClassName=$moduleClassName;
        return $this;
    }
    public function setBelongToModule($module){
        $this->belongToModule=$module;
        return $this;
    }
    public function bindActionIterator(ActionIterator $iterator){
        $this->actionIterator=$iterator;
        $iterator->bindControllerIterator($this);
        return $this;
    }
    public function generateList(){
        list($modulePath,$moduleNamespace)=$this->moduleIterator->getModulePathAndNamespace($this->moduleClassName);
        $controllerPath=$this->getControllerPath($modulePath);
        $resource=opendir($controllerPath);
        $list=[];
        while ($fileName=readdir($resource)){
            if($fileName=='.' || $fileName=='..')continue;
            $fileName=basename($fileName,'.php');
            $list[]=[$fileName,$this->getControllerNamespace($moduleNamespace).'\\'.$fileName];
        }
        return $list;
    }

    public function handle($controller){
        list($controllerName,$controllerClass)=$controller;
        $this->actionIterator->setBelongToController($controllerName)->setControllerClass($controllerClass)->traverse();
    }

    protected function getControllerPath($modulePath){
        return $modulePath.'/controllers';
    }

    protected function getControllerNamespace($moduleNamespace){
        return $moduleNamespace.'\controllers';
    }

    public function getModuleName(){
        return $this->belongToModule;
    }
}

class  ActionIterator extends Iterator{
    protected $docParser;
    protected $belongToController;
    protected $controllerIterator;
    protected $controllerClass;
    protected $interfaceOperator;
    public function setDocParser(DocParser $parser)
    {
        $this->docParser = $parser;
        return $this;
    }
    public function bindInterfaceOperator(InterfaceOperateAbstract $operator){
        $this->interfaceOperator=$operator;
        return $this;
    }
    public function bindControllerIterator(ControllerIterator $iterator){
        $this->controllerIterator=$iterator;
        return $this;
    }
    public function setBelongToController($controller){
        $this->belongToController=$controller;
        return $this;
    }
    public function setControllerClass($class){
        $this->controllerClass=$class;
        return $this;
    }
    public function generateList(){
        $reflect=new \ReflectionClass($this->controllerClass);
        $methods=$reflect->getMethods();
        $list=[];
        foreach ($methods as $method){
            if(strpos($method->name,'action')===0 && $method->name!='actions'){
                $info=$this->getDocComment($method);
                $info['actionName']=lcfirst(str_replace('action','',$method->name));
                $list[]=$info;
            }
        }
        return $list;
    }

    protected function getDocComment(\ReflectionMethod $method){
        return $this->docParser->parse($method->getDocComment());
    }
    public function getControllerName(){
        return lcfirst(substr($this->belongToController,0,-10));
    }
    public function handle($action){
        $this->interfaceOperator->module=$this->controllerIterator->getModuleName();
        $this->interfaceOperator->controller=$this->getControllerName();
        $this->interfaceOperator->action=$action['actionName'];
        $this->interfaceOperator->name=empty($action['name'])?'':$action['name'];
        $this->interfaceOperator->desc=empty($action['desc'])?'':$action['desc'];
        $this->interfaceOperator->moduleCode=empty($action['moduleCode'])?'':$action['moduleCode'];
        $this->interfaceOperator->status=empty($action['status'])?0:(int)$action['status'];
        $this->interfaceOperator->handle();
    }
}

