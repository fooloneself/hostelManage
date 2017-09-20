<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%role_privilege}}".
 *
 * @property integer $role_id
 * @property string $module_code
 * @property string $privilege_code
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
            [['role_id', 'module_code', 'privilege_code'], 'required'],
            [['role_id'], 'integer'],
            [['module_code', 'privilege_code'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'module_code' => 'Module Code',
            'privilege_code' => 'Privilege Code',
        ];
    }
}
