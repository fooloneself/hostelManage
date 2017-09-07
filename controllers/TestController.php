<?php
namespace app\controllers;
use common\components\Controller;
use common\components\validator\Signature;

class TestController extends Controller{
    protected function request($url,$params){
        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch,CURLOPT_HEADER,0);
        $start=microtime(true);
        $res= curl_exec($ch);
        var_dump(microtime(true)-$start);
        return $res;
    }
    public function actionIndex(){
        $params=[
            'mchNo'=>'44551515',
            'nonceStr'=>'5451a15f1g',
            'schoolId'=>77,
            'dataTime'=>1501070081,
            'page'=>1,
            'pageSize'=>20
        ];
        $signature=new Signature();
        $signature->setParams($params);
        $signature->setMchNo($params['mchNo']);
        $params['sign']=$signature->sign();
        //$url='http://192.168.2.240:9983/electronic/access-control/mac-rules';
        $url='http://www.openplatform.com/electronic/personal-card/student';
        //$url='http://www.lepeiopen.com/electronic/entry-data/list';
        return $this->request($url,$params);
    }

    public function actionMakeData(){

    }
}