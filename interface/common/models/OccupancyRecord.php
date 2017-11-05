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
 * @property integer $premises_id
 * @property integer $room_number
 * @property string $mobile
 * @property string $person_name
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
            [['order_id', 'room_id', 'mch_id', 'premises_id', 'room_number'], 'integer'],
            [['address'], 'string'],
            [['mobile'], 'string', 'max' => 50],
            [['person_name'], 'string', 'max' => 100],
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
            'premises_id' => 'Premises ID',
            'room_number' => 'Room Number',
            'mobile' => 'Mobile',
            'person_name' => 'Person Name',
            'address' => 'Address',
        ];
    }
}
