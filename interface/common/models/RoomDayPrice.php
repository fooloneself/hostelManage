<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%room_day_price}}".
 *
 * @property integer $id
 * @property integer $room_id
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
            [['room_id', 'year', 'month', 'day'], 'integer'],
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
            'room_id' => 'Room ID',
            'year' => 'Year',
            'month' => 'Month',
            'day' => 'Day',
            'price' => 'Price',
        ];
    }
}
