<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_pay_detail}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $amount
 * @property string $expense_item
 * @property string $channel
 */
class OrderPayDetail extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_pay_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id'], 'integer'],
            [['amount'], 'number'],
            [['expense_item', 'channel'], 'string', 'max' => 100],
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
            'amount' => 'Amount',
            'expense_item' => 'Expense Item',
            'channel' => 'Channel',
        ];
    }
}
