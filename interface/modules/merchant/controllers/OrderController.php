<?php
namespace modules\merchant\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use service\order\OrderManger;
use service\order\Room;

class OrderController extends Controller{

    /**
     * 入住
     * @return mixed
     */
    public function actionOccupancy(){
        return $this->place('occupancy');
    }

    /**
     * 预定
     */
    public function actionReverse(){
        return $this->place('reverse');
    }

    /**
     * 下单
     * @param $operate
     * @return mixed
     */
    protected function place($operate){
        $lodgers=\Yii::$app->requestHelper->post('lodgers',[],'array');
        $guest=\Yii::$app->requestHelper->post('guest',[],'array');
        $roomId=\Yii::$app->requestHelper->post('roomId',0,'int');
        $price=\Yii::$app->requestHelper->post('price',0,'float');
        $mark=\Yii::$app->requestHelper->post('mark','','string');
        $pay=\Yii::$app->requestHelper->post('pay',[],'array');
        $type=\Yii::$app->requestHelper->post('type',1,'int');
        $number=\Yii::$app->requestHelper->post('number',0,'int');
        $channel=\Yii::$app->requestHelper->post('channel');
        $merchant=\Yii::$app->user->getAdmin()->getMerchant();
        $startTime=$_SERVER['REQUEST_TIME'];
        if($type==1){
            $endTime=strtotime(date('Y-m-d',$startTime+86400*$number).' '.$merchant->getSetting()->check_out_time);
        }else{
            $endTime=$startTime+$number;
        }
        $manager=new OrderManger($merchant);
        $transaction=\Yii::$app->db->beginTransaction();
        $lodgers[]=$guest;
        $manager->pay($pay)
            ->room([['roomId'=>$roomId,'price'=>$price,'type'=>$type,'start'=>$startTime,'end'=>$endTime]])
            ->mark($mark,$channel)
            ->lodger($lodgers)
            ->guest($guest['mobile'],$guest['name']);
        if(!$manager->$operate()){
            $transaction->rollBack();
            return \Yii::$app->responseHelper->error($manager->getError())->response();
        }else{
            $transaction->commit();
            return \Yii::$app->responseHelper->success()->response();
        }
    }
    /**
     * 入住-预定
     */
    public function actionOccupancyByReserve(){

    }
}