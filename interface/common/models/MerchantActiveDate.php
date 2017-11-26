<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%merchant_active_date}}".
 *
 * @property integer $id
 * @property integer $active_id
 * @property integer $active_date
 */
class MerchantActiveDate extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%merchant_active_date}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active_id', 'active_date'], 'integer'],
            [['active_id', 'active_date'], 'unique', 'targetAttribute' => ['active_id', 'active_date'], 'message' => 'The combination of Active ID and Active Date has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'active_id' => 'Active ID',
            'active_date' => 'Active Date',
        ];
    }
}
