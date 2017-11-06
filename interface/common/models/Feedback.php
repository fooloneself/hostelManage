<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%feedback}}".
 *
 * @property string $id
 * @property integer $mch_id
 * @property integer $create_time
 * @property integer $answer_time
 * @property integer $feedback_admin_id
 * @property integer $answer_admin_id
 * @property integer $status
 * @property string $content
 * @property string $answer
 */
class Feedback extends \common\components\ActiveRecord
{
    const STATUS_HANDLING=0;//未处理，正在处理中
    const STATUS_HANDLED=1; //已处理，问题已解决
    const STATUS_FAIL=2;  //已处理，问题未解决
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
            [['mch_id', 'create_time', 'answer_time', 'feedback_admin_id', 'answer_admin_id', 'status'], 'integer'],
            [['content', 'answer'], 'string'],
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
            'answer_time' => 'Answer Time',
            'feedback_admin_id' => 'Feedback Admin ID',
            'answer_admin_id' => 'Answer Admin ID',
            'status' => 'Status',
            'content' => 'Content',
            'answer' => 'Answer',
        ];
    }
}
