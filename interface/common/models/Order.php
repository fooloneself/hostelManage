<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $id
 * @property integer $mch_id
 * @property integer $guest_id
 * @property integer $place_time
 * @property integer $type
 * @property integer $status
 * @property string $amount_payable
 * @property string $amount_paid
 * @property string $amount_deffer
 * @property string $order_no
 * @property string $channel
 * @property string $mark
 */
class Order extends \common\components\ActiveRecord
{
    const STATUS_CANCEL     =0;//撤销
    const STATUS_REVERSE    =1;//预定
    const STATUS_OCCUPANCY  =2;//入住
    const STATUS_CHECK_OUT  =3;//退房
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id', 'guest_id', 'place_time', 'order_no'], 'required'],
            [['mch_id', 'guest_id', 'place_time', 'type', 'status'], 'integer'],
            [['amount_payable', 'amount_paid', 'amount_deffer'], 'number'],
            [['order_no'], 'string', 'max' => 20],
            [['channel'], 'string', 'max' => 3],
            [['mark'], 'string', 'max' => 600],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mch_id' => 'Mch ID',
            'guest_id' => 'Guest ID',
            'place_time' => 'Place Time',
            'type' => 'Type',
            'status' => 'Status',
            'amount_payable' => 'Amount Payable',
            'amount_paid' => 'Amount Paid',
            'amount_deffer' => 'Amount Deffer',
            'order_no' => 'Order No',
            'channel' => 'Channel',
            'mark' => 'Mark',
        ];
    }
}
