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
 * @property integer $channel
 * @property integer $is_temporary
 * @property integer $status
 * @property integer $is_reverse
 * @property integer $is_settlement
 * @property string $amount
 * @property string $amount_payable
 * @property string $amount_paid
 * @property string $amount_deffer
 * @property string $abnormal_type
 * @property string $order_no
 * @property string $mark
 */
class Order extends \common\components\ActiveRecord
{
    const STATUS_ABNORMAL =0;//异常
    const STATUS_NORMAL  =1;//正常
    const SETTLE_NO      =0;//未结单
    const SETTLE_YES     =1;//已结单
    const REVERSE_NO     =0;//非预订单
    const REVERSE_YES    =1;//预订单

    const ABNORMAL_CANCEL  =1;//撤销
    const ABNORMAL_DEFFER   =2;//金额不正确
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
            [['mch_id', 'guest_id', 'place_time', 'channel', 'is_temporary', 'status', 'is_reverse', 'is_settlement'], 'integer'],
            [['amount', 'amount_payable', 'amount_paid', 'amount_deffer'], 'number'],
            [['abnormal_type'], 'string', 'max' => 100],
            [['order_no'], 'string', 'max' => 20],
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
            'channel' => 'Channel',
            'is_temporary' => 'Is Temporary',
            'status' => 'Status',
            'is_reverse' => 'Is Reverse',
            'is_settlement' => 'Is Settlement',
            'amount' => 'Amount',
            'amount_payable' => 'Amount Payable',
            'amount_paid' => 'Amount Paid',
            'amount_deffer' => 'Amount Deffer',
            'abnormal_type' => 'Abnormal Type',
            'order_no' => 'Order No',
            'mark' => 'Mark',
        ];
    }
}
