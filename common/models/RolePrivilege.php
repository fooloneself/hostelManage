<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%role_privilege}}".
 *
 * @property integer $role_id
 * @property integer $privilege_code
 */
class RolePrivilege extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%role_privilege}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'privilege_code'], 'required'],
            [['role_id', 'privilege_code'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'privilege_code' => 'Privilege Code',
        ];
    }
}
