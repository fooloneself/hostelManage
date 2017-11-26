<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%merchant_activity_condition}}".
 *
 * @property integer $active_id
 * @property integer $type
 * @property integer $condition_identity
 */
class MerchantActivityCondition extends \common\components\ActiveRecord
{
    const TYPE_MEMBER_RANK         =1;//会员等级
    const TYPE_ROOM_ID             =2;//房间ID
    const TYPE_FEE_TYPE            =3;//费用类型
    const TYPE_CONSUMPTION_FULL   =4;//消费满
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%merchant_activity_condition}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active_id', 'type', 'condition_identity'], 'required'],
            [['active_id', 'type', 'condition_identity'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'active_id' => 'Active ID',
            'type' => 'Type',
            'condition_identity' => 'Condition Identity',
        ];
    }
}
