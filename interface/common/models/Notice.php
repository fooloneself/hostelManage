<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%notice}}".
 *
 * @property integer $id
 * @property integer $admin_id
 * @property integer $create_time
 * @property integer $public_time
 * @property integer $revoke_time
 * @property integer $status
 * @property string $title
 * @property string $content
 */
class Notice extends \common\components\ActiveRecord
{
    const STATUS_DRAFT  =1;//草稿
    const STATUS_PUBLIC =2;//发布
    const STATUS_REVOKE =3;//撤回
    public static $status=[
        self::STATUS_PUBLIC=>'发送',
        self::STATUS_DRAFT=>'草稿',
        self::STATUS_REVOKE=>'撤回'
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id', 'create_time', 'public_time', 'revoke_time', 'status'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_id' => 'Admin ID',
            'create_time' => 'Create Time',
            'public_time' => 'Public Time',
            'revoke_time' => 'Revoke Time',
            'status' => 'Status',
            'title' => 'Title',
            'content' => 'Content',
        ];
    }
}
