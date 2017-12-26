<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_activity}}".
 *
 * @property integer $order_id
 * @property integer $activity_id
 * @property string $discount_amount
 */
class OrderActivity extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_activity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'activity_id'], 'integer'],
            [['discount_amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'activity_id' => 'Activity ID',
            'discount_amount' => 'Discount Amount',
        ];
    }
}
