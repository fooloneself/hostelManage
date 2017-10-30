<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_occupancy_room}}".
 *
 * @property integer $order_id
 * @property integer $room_id
 * @property integer $actual_in_time
 * @property integer $actual_out_time
 * @property string $amount
 */
class OrderOccupancyRoom extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_occupancy_room}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'room_id'], 'required'],
            [['order_id', 'room_id', 'actual_in_time', 'actual_out_time'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'room_id' => 'Room ID',
            'actual_in_time' => 'Actual In Time',
            'actual_out_time' => 'Actual Out Time',
            'amount' => 'Amount',
        ];
    }
}
