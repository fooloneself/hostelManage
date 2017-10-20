<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%notice_read}}".
 *
 * @property integer $admin_id
 * @property integer $notice_id
 * @property integer $read_time
 */
class NoticeRead extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notice_read}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id', 'notice_id'], 'required'],
            [['admin_id', 'notice_id', 'read_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'admin_id' => 'Admin ID',
            'notice_id' => 'Notice ID',
            'read_time' => 'Read Time',
        ];
    }
}
