<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%guest}}".
 *
 * @property integer $id
 * @property integer $mch_id
 * @property integer $create_time
 * @property integer $member_id
 * @property string $mobile
 * @property string $person_name
 */
class Guest extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%guest}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id', 'create_time', 'member_id'], 'integer'],
            [['mobile'], 'required'],
            [['mobile'], 'string', 'max' => 50],
            [['person_name'], 'string', 'max' => 200],
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
            'member_id' => 'Member ID',
            'mobile' => 'Mobile',
            'person_name' => 'Person Name',
        ];
    }
}
