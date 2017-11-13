<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_cost_detail}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $room_id
 * @property integer $date
 * @property integer $year
 * @property integer $month
 * @property integer $day
 * @property string $amount
 */
class OrderCostDetail extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_cost_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'room_id'], 'required'],
            [['order_id', 'room_id', 'date', 'year', 'month', 'day'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'room_id' => 'Room ID',
            'date' => 'Date',
            'year' => 'Year',
            'month' => 'Month',
            'day' => 'Day',
            'amount' => 'Amount',
        ];
    }
}
