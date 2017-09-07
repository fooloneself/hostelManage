<?php
namespace app\controllers;
use common\components\Controller;
use common\models\TestInterface;
use common\models\TestInterfaceParam;
use common\models\TestInterfaceResParam;

class InterfaceController extends Controller{

    public function actionList(){
        $list=TestInterface::find()
            ->alias('ti')
            ->leftJoin(TestInterfaceParam::tableName().' tip','ti.id=tip.interface_id')
            ->leftJoin(TestInterfaceResParam::tableName().' tirp','tirp.interface_id=ti.id')
            ->asArray()->all();
        var_dump($list);die;
    }

    public function actionEdit(){
        return $this->render('edit');
    }

    public function actionAdd(){
        $params=json_decode(\Yii::$app->request->getRawBody(),true);
        $interfaceId=$this->addInterface($params['url'],$params['description'],$params['version']);
        $this->addRequestParams($interfaceId,$params['request']);
        $this->addResponseParam($interfaceId,$params['response']);
        return $this->redirect('/interface/edit');
    }
    protected function addInterface($url,$desc,$version){
        $model=new TestInterface();
        $model->url=$url;
        $model->description=$desc;
        $model->version=$version;
        if($model->insert()){
            return $model->id;
        }
        return false;
    }

    protected function addRequestParams($interfaceId,$request){
        $model=new TestInterfaceParam();
        if(empty($request))return true;
        foreach ($request as $item){
            if(empty($item))continue;
            $model->isNewRecord=true;
            $model->name=$item['name'];
            $model->type=$item['type'];
            $model->label=$item['label'];
            $model->default=$item['default'];
            $model->description=$item['description'];
            $model->required=$item['required'];
            $model->interface_id=$interfaceId;
            $model->insert();
        }
    }
    protected function getResponseParamModel(){
        static $model;
        if($model===null)$model=new TestInterfaceResParam();
        return $model;
    }
    protected function addResponseParam($interfaceId,$responses,$pid=0){
        if(empty($responses))return true;
        foreach ($responses as $response){

        }
    }
}