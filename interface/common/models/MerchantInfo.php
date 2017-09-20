<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%merchant_info}}".
 *
 * @property integer $mch_id
 * @property string $business_license_no
 * @property string $business_license
 * @property string $telephone
 * @property string $mobile
 * @property string $introduce
 */
class MerchantInfo extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%merchant_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id'], 'required'],
            [['mch_id'], 'integer'],
            [['introduce'], 'string'],
            [['business_license_no', 'telephone'], 'string', 'max' => 50],
            [['business_license'], 'string', 'max' => 200],
            [['mobile'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mch_id' => 'Mch ID',
            'business_license_no' => 'Business License No',
            'business_license' => 'Business License',
            'telephone' => 'Telephone',
            'mobile' => 'Mobile',
            'introduce' => 'Introduce',
        ];
    }
}
