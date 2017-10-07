<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%merchant_set}}".
 *
 * @property integer $mch_id
 * @property integer $auto_close_switch
 * @property integer $reserve_switch
 * @property integer $hour_room_switch
 * @property integer $reserve_retention_time
 * @property string $check_out_time
 * @property string $hour_room_start_time
 * @property string $hour_room_end_time
 */
class MerchantSet extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%merchant_set}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id'], 'required'],
            [['mch_id', 'auto_close_switch', 'reserve_switch', 'hour_room_switch', 'reserve_retention_time'], 'integer'],
            [['check_out_time'], 'string', 'max' => 10],
            [['hour_room_start_time', 'hour_room_end_time'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mch_id' => 'Mch ID',
            'auto_close_switch' => 'Auto Close Switch',
            'reserve_switch' => 'Reserve Switch',
            'hour_room_switch' => 'Hour Room Switch',
            'reserve_retention_time' => 'Reserve Retention Time',
            'check_out_time' => 'Check Out Time',
            'hour_room_start_time' => 'Hour Room Start Time',
            'hour_room_end_time' => 'Hour Room End Time',
        ];
    }
}
