<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admin_role}}".
 *
 * @property integer $admin_id
 * @property integer $role_id
 */
class AdminRole extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id', 'role_id'], 'required'],
            [['admin_id', 'role_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'admin_id' => 'Admin ID',
            'role_id' => 'Role ID',
        ];
    }

    /**
     * 获取管理员的全部权限
     * @param $adminId
     * @return array
     */
    public static function getPrivilegeByAdminId($adminId){
        return self::find()
            ->alias('ar')
            ->select('rp.privilege_code')
            ->leftJoin(RolePrivilege::tableName().' rp','rp.role_id=ar.role_id')
            ->where(['ar.admin_id'=>$adminId])
            ->column();
    }
}
