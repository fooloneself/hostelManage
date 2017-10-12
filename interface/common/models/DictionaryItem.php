<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%dictionary_item}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $key
 * @property string $value
 * @property integer $order
 */
class DictionaryItem extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dictionary_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'order'], 'integer'],
            [['code'], 'string', 'max' => 100],
            [['key', 'value'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'key' => 'Key',
            'value' => 'Value',
            'order' => 'Order',
        ];
    }
}
