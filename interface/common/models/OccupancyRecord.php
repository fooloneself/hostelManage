<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%occupancy_record}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $room_id
 * @property integer $mch_id
 * @property integer $city
 * @property integer $premises_id
 * @property integer $actual_in_time
 * @property integer $actual_out_time
 * @property integer $number_type
 * @property string $number
 * @property string $person_name
 * @property integer $room_floor
 * @property integer $room_number
 * @property string $address
 */
class OccupancyRecord extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%occupancy_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'room_id', 'mch_id'], 'required'],
            [['order_id', 'room_id', 'mch_id', 'city', 'premises_id', 'actual_in_time', 'actual_out_time', 'number_type', 'room_floor', 'room_number'], 'integer'],
            [['address'], 'string'],
            [['number', 'person_name'], 'string', 'max' => 100],
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
            'mch_id' => 'Mch ID',
            'city' => 'City',
            'premises_id' => 'Premises ID',
            'actual_in_time' => 'Actual In Time',
            'actual_out_time' => 'Actual Out Time',
            'number_type' => 'Number Type',
            'number' => 'Number',
            'person_name' => 'Person Name',
            'room_floor' => 'Room Floor',
            'room_number' => 'Room Number',
            'address' => 'Address',
        ];
    }
}
