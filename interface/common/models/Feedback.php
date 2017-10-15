<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%feedback}}".
 *
 * @property string $id
 * @property integer $mch_id
 * @property integer $create_time
 * @property integer $admin_id
 * @property integer $status
 * @property string $content
 */
class Feedback extends \common\components\ActiveRecord
{
    const STATUS_HANDLING=0;//未处理，正在处理中
    const STATUS_HANDLED=1; //已处理，问题已解决
    const STATUS_FAIL=2;    //已处理，问题未解决
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%feedback}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id'], 'required'],
            [['mch_id', 'create_time', 'admin_id', 'status'], 'integer'],
            [['content'], 'string'],
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
            'create_time' => 'Create Time',
            'admin_id' => 'Admin ID',
            'status' => 'Status',
            'content' => 'Content',
        ];
    }
}
