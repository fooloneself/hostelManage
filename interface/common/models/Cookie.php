<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%cookie}}".
 *
 * @property integer $uid
 * @property string $cookie_uuid
 * @property integer $logindate
 * @property integer $clientType
 * @property string $pushid
 */
class Cookie extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cookie}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'logindate', 'clientType'], 'integer'],
            [['cookie_uuid', 'pushid'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'cookie_uuid' => 'Cookie Uuid',
            'logindate' => 'Logindate',
            'clientType' => 'Client Type',
            'pushid' => 'Pushid',
        ];
    }

    public static function getAppInfo($userId){
        $res=self::find()->select('clienttype,pushid')->where(['uid'=>$userId])->asArray()->one();
        if(empty($res)){
            return ['',''];
        }else{
            return [$res['pushid'],$res['clienttype']];
        }
    }

    /**
     * 获取回话信息
     * @param $userId
     * @return mixed
     */
    public static function getSessionInfo($userId){
        return self::find()->where(['uid'=>$userId])->asArray()->find();
    }
}
