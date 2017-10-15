<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%room_day_price}}".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $mch_id
 * @property integer $year
 * @property integer $month
 * @property integer $day
 * @property string $price
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
            [['type_id', 'mch_id', 'year', 'month', 'day'], 'integer'],
            [['price'], 'number'],
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
            'year' => 'Year',
            'month' => 'Month',
            'day' => 'Day',
            'price' => 'Price',
        ];
    }
}
