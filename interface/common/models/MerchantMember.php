<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%merchant_member}}".
 *
 * @property integer $mch_id
 * @property integer $member_id
 * @property integer $rank
 * @property string $consumption_amount
 */
class MerchantMember extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%merchant_member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id', 'member_id'], 'required'],
            [['mch_id', 'member_id', 'rank'], 'integer'],
            [['consumption_amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mch_id' => 'Mch ID',
            'member_id' => 'Member ID',
            'rank' => 'Rank',
            'consumption_amount' => 'Consumption Amount',
        ];
    }
}
