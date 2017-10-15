<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%room_week_price}}".
 *
 * @property integer $type_id
 * @property integer $mch_id
 * @property string $monday
 * @property string $tuesday
 * @property string $wensday
 * @property string $thursday
 * @property string $friday
 * @property string $saturday
 * @property string $sunday
 */
class RoomWeekPrice extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%room_week_price}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id'], 'required'],
            [['type_id', 'mch_id'], 'integer'],
            [['monday', 'tuesday', 'wensday', 'thursday', 'friday', 'saturday', 'sunday'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type_id' => 'Type ID',
            'mch_id' => 'Mch ID',
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wensday' => 'Wensday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];
    }
}
