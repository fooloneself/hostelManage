<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%room_day_price}}".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $mch_id
 * @property integer $start_date
 * @property integer $end_date
 * @property string $price
 * @property string $mark
 */
class RoomDayPrice extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%room_day_price}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'mch_id', 'start_date', 'end_date'], 'integer'],
            [['price'], 'number'],
            [['mark'], 'string', 'max' => 800],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'mch_id' => 'Mch ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'price' => 'Price',
            'mark' => 'Mark',
        ];
    }
}
