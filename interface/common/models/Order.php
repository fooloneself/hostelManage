<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $id
 * @property integer $mch_id
 * @property integer $user_id
 * @property integer $place_time
 * @property integer $status
 * @property string $amount_payable
 * @property string $amount_paid
 * @property string $amount_deffer
 * @property string $order_no
 * @property string $channel
 */
class Order extends \common\components\ActiveRecord
{
    const STATUS_CANCEL =3;
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
            [['mch_id', 'user_id', 'place_time', 'order_no'], 'required'],
            [['mch_id', 'user_id', 'place_time', 'status'], 'integer'],
            [['amount_payable', 'amount_paid', 'amount_deffer'], 'number'],
            [['order_no'], 'string', 'max' => 20],
            [['channel'], 'string', 'max' => 3],
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
            'user_id' => 'User ID',
            'place_time' => 'Place Time',
            'status' => 'Status',
            'amount_payable' => 'Amount Payable',
            'amount_paid' => 'Amount Paid',
            'amount_deffer' => 'Amount Deffer',
            'order_no' => 'Order No',
            'channel' => 'Channel',
        ];
    }
}
