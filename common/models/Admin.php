<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admin}}".
 *
 * @property integer $id
 * @property integer $mch_id
 * @property integer $expire
 * @property integer $last_login_time
 * @property integer $is_super
 * @property string $user_name
 * @property string $password
 * @property string $token
 */
class Admin extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id', 'expire', 'last_login_time', 'is_super'], 'integer'],
            [['user_name', 'password'], 'required'],
            [['user_name', 'password', 'token'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mch_id' => 'Mch ID',
            'expire' => 'Expire',
            'last_login_time' => 'Last Login Time',
            'is_super' => 'Is Super',
            'user_name' => 'User Name',
            'password' => 'Password',
            'token' => 'Token',
        ];
    }
}
