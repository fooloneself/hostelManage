<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%merchant_member_birthday_welfare}}".
 *
 * @property integer $member_rank_id
 * @property integer $activity_id
 */
class MerchantMemberBirthdayWelfare extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%merchant_member_birthday_welfare}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_rank_id', 'activity_id'], 'required'],
            [['member_rank_id', 'activity_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_rank_id' => 'Member Rank ID',
            'activity_id' => 'Activity ID',
        ];
    }
}
