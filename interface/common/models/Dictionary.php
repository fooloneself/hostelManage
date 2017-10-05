<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%dictionary}}".
 *
 * @property integer $id
 * @property string $label
 * @property string $key
 * @property string $value
 */
class Dictionary extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dictionary}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'key', 'value'], 'required'],
            [['label', 'key', 'value'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Label',
            'key' => 'Key',
            'value' => 'Value',
        ];
    }
}
