<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%merchant_member_rank_divide}}".
 *
 * @property string $id
 * @property integer $mch_id
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $min_integral
 * @property integer $birthday_welfare_expire
 * @property string $min_consumption_amount
 * @property string $name
 * @property string $mark
 */
class MerchantMemberRankDivide extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%merchant_member_rank_divide}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id', 'name'], 'required'],
            [['mch_id', 'create_time', 'update_time', 'min_integral', 'birthday_welfare_expire'], 'integer'],
            [['min_consumption_amount'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['mark'], 'string', 'max' => 1000],
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
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'min_integral' => 'Min Integral',
            'birthday_welfare_expire' => 'Birthday Welfare Expire',
            'min_consumption_amount' => 'Min Consumption Amount',
            'name' => 'Name',
            'mark' => 'Mark',
        ];
    }
}
