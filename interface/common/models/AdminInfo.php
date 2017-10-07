<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admin_info}}".
 *
 * @property integer $admin_id
 * @property integer $birthday
 * @property integer $sex
 * @property string $mobile
 * @property string $name
 */
class AdminInfo extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id'], 'required'],
            [['admin_id', 'birthday', 'sex'], 'integer'],
            [['mobile'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'admin_id' => 'Admin ID',
            'birthday' => 'Birthday',
            'sex' => 'Sex',
            'mobile' => 'Mobile',
            'name' => 'Name',
        ];
    }
}
