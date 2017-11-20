<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%dictionary}}".
 *
 * @property integer $id
 * @property string $label
 * @property string $code
 * @property string $introduce
 */
class Dictionary extends \common\components\ActiveRecord
{
    const DICTIONARY_SEX='sex';
    const DICTIONARY_ROOM_SERVER='room_server';
    const DICTIONARY_NUMBER_TYPE='number_type';
    const DICTIONARY_ORDER_ABNORMAL='order_abnormal';
    const DICTIONARY_PAYMENT_CHANNEL='payment_channel';
    const DICTIONARY_EXPANSE_ITEM='expanse_item';
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
            [['label', 'code', 'introduce'], 'required'],
            [['label', 'code'], 'string', 'max' => 100],
            [['introduce'], 'string', 'max' => 500],
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
            'code' => 'Code',
            'introduce' => 'Introduce',
        ];
    }
}
