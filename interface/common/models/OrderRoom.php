<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_room}}".
 *
 * @property integer $order_id
 * @property integer $room_id
 * @property integer $plan_in_time
 * @property integer $plan_out_time
 * @property string $amount
 * @property integer $occupancy_status
 */
class OrderRoom extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_room}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'room_id', 'plan_in_time', 'plan_out_time'], 'required'],
            [['order_id', 'room_id', 'plan_in_time', 'plan_out_time', 'occupancy_status'], 'integer'],
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
            'plan_in_time' => 'Plan In Time',
            'plan_out_time' => 'Plan Out Time',
            'amount' => 'Amount',
            'occupancy_status' => 'Occupancy Status',
        ];
    }
}
