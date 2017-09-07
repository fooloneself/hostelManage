<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%app_user}}".
 *
 * @property integer $app_user_id
 * @property string $username
 * @property string $password
 * @property integer $create_time
 * @property integer $last_login_time
 * @property string $last_login_version
 * @property string $paypassword
 * @property string $paypwdkey
 * @property integer $no_paypwd
 * @property integer $disable
 */
class AppUser extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'last_login_time', 'no_paypwd', 'disable'], 'integer'],
            [['username', 'password', 'paypwdkey'], 'string', 'max' => 32],
            [['last_login_version', 'paypassword'], 'string', 'max' => 64],
            [['username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_user_id' => 'App User ID',
            'username' => 'Username',
            'password' => 'Password',
            'create_time' => 'Create Time',
            'last_login_time' => 'Last Login Time',
            'last_login_version' => 'Last Login Version',
            'paypassword' => 'Paypassword',
            'paypwdkey' => 'Paypwdkey',
            'no_paypwd' => 'No Paypwd',
            'disable' => 'Disable',
        ];
    }

    public static function getMobile($appUserId){
        $res=self::find()->select('username')->where(['app_user_id'=>$appUserId])->asArray()->one();
        if(empty($res['username'])){
            return false;
        }else{
            return $res['username'];
        }
    }

    public static function getUserInfo($userId){
        return self::find()->alias('au')
                ->select('au.username as mobile,au.disable,au.password,if(au.paypassword == null,1,0) as has_pay_password,aui.real_name,aui.portrait')
                ->leftJoin(AppUserInfo::tableName().' aui','aui.app_user_id=au.app_user_id')
                ->where(['au.app_user_id'=>$userId])
                ->one();
    }

    /**
     * 通过账户名获取登录密码
     * @param $userName
     * @return bool
     */
    public static function getUserByUserName($userName){
        $res=self::find()->select('app_user_id,password,disable')->where(['username'=>$userName])->asArray()->one();
        if(empty($res['password'])){
            return false;
        }else{
            return $res;
        }
    }
}
