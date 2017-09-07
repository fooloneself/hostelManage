<?php
namespace app\controllers;
use common\components\Controller;
use common\components\ErrorManager;
use common\components\Validate;
use service\user\LoginValidateServer;
use service\user\UserLoginServer;

class SiteController extends Controller{
    public function actionLogin(){
        $helper=\Yii::$app->requestHelper;
        $userName=$helper->post('username');
        $pwd=$helper->post('password');
        $pushId=$helper->post('pushid');
        $version=$helper->getVersion();
        $clientType=$helper->getClientType();
        $validate=new LoginValidateServer($userName,$pwd,$pushId,$clientType,$version);
        $server=new UserLoginServer($userName,$pwd,$pushId,$version);
        if($server->login()==false){
            return \Yii::$app->responseHelper->setErrorObj($server->getError())->response();
        }
        $cookie = M('cookie')->where(array('uid' => (int) $user['app_user_id']))->find();

        $params['uid'] = (int) $user['app_user_id'];
        $params['pushid'] = $arr['pushid'];
        $params['logindate'] = time();
        if ($_SERVER['HTTP_USER_AGENT'] == 'iPhone') {
            $params['clientType'] = 2;
        } else {
            $params['clientType'] = 1;
        }
        $params['cookie_uuid'] = $this->uuid();

        if (!$cookie) {
            //保存cookie
            M('cookie')->add($params);
        }
        //修改cookie
        M('cookie')->save($params);


        if (!$params['cookie_uuid']) {
            $params['cookie_uuid'] = $cookie['cookie_uuid'];
        }

        //查出关联学生信息


        $detail = array();
        $detail['username'] = $user['username'];
        $detail['token'] = $params['cookie_uuid'];
        $detail['id'] = $user['app_user_id'];
        $detail['list'] = [];
        $detail['is_set_paypwd'] = empty($user['paypassword']) ? 0 : 1;
        $detail['auth'] = [];
        //查询授权手机号
        $telephones = M('app_user_auth a')->join(' t_app_user b on a.authed_uid=b.app_user_id')->where(array('a.auth_uid' => $user['app_user_id'], 'a.is_disable' => 0))->field('b.username')->select();
        if ($telephones) {
            $detail['auth'] = $telephones;
        }
        $list1 = M('app_user_relation')->where(array('is_binding' => 1, 'app_user_id' => $user['app_user_id']))->field('person_id')->select();
        $list2 = M('app_user_relation a')->join(' t_app_user_auth b on a.app_user_id=b.auth_uid')->where(array('b.is_disable' => 0, 'a.is_binding' => 1, 'b.authed_uid' => $user['app_user_id']))->field('a.person_id')->select();
        $list = array_merge($list1, $list2);
        $type = [];
        foreach ($list as $key => $value) {
            $info = M('person_info_v')->field('id,name,sex,type,ic_card_no,number,grade_type,grade_name,classes_name,school_id,school_name,school_logo')->where(array('id' => (int) $value['person_id']))->order('create_time desc')->find();
            $info['name'] = usernameforasterisk($info['name']);
            $studyName = schoolLevel($info['grade_type']);
            $info['classes_name'] = $studyName . $info['grade_name'] . $info['classes_name'];
            $detail['list'][$key] = $info;
            $type[] =(int)$info['type'];
        }
        $type=array_unique($type);
        $detail['r_token']=$this->getRToken($detail['id']);
        $detail['type'] = array_sum($type);
        appSuccess($detail);
    }
}