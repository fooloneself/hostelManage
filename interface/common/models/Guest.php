<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%guest}}".
 *
 * @property integer $id
 * @property integer $mch_id
 * @property integer $number_type
 * @property string $number
 * @property integer $create_time
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
            [['mch_id', 'number_type', 'create_time'], 'integer'],
            [['number_type'], 'required'],
            [['number'], 'string', 'max' => 100],
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
            'number_type' => 'Number Type',
            'number' => 'Number',
            'create_time' => 'Create Time',
        ];
    }
}
