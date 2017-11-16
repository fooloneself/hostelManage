<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_room}}".
 *
 * @property integer $order_id
 * @property integer $room_id
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $status
 * @property integer $type
 * @property string $amount
 */
class OrderRoom extends \common\components\ActiveRecord
{
    const STATUS_CANCEL    =0;//撤销
    const STATUS_REVERSE   =1;//预定
    const STATUS_OCCUPANCY =2;//入住
    const STATUS_CHECK_OUT =3;//退房
    //类型
    const TYPE_DAY     =1;//天
    const TYPE_CLOCK   =2;//钟点
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
            [['order_id', 'room_id'], 'required'],
            [['order_id', 'room_id', 'start_time', 'end_time', 'status', 'type'], 'integer'],
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
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'status' => 'Status',
            'type' => 'Type',
            'amount' => 'Amount',
        ];
    }
}
