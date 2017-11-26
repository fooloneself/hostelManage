<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%merchant_activity}}".
 *
 * @property integer $id
 * @property integer $mch_id
 * @property integer $create_time
 * @property integer $status
 * @property integer $type
 * @property string $discount
 * @property string $name
 * @property string $mark
 */
class MerchantActivity extends \common\components\ActiveRecord
{
    const TYPE_DISCOUNT        =1;//折扣
    const TYPE_FULL_CUT        =2;//满减
    const TYPE_SPECIAL_OFFER   =3;//特价

    const STATUS_USABLE    =1; //可用
    const STATUS_DISABLE   =0;//不可用
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%merchant_activity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id', 'create_time', 'status', 'type'], 'integer'],
            [['discount'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['mark'], 'string', 'max' => 800],
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
            'status' => 'Status',
            'type' => 'Type',
            'discount' => 'Discount',
            'name' => 'Name',
            'mark' => 'Mark',
        ];
    }
}
