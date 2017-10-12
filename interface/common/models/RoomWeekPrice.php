<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%room_week_price}}".
 *
 * @property integer $room_id
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
            [['room_id'], 'required'],
            [['room_id'], 'integer'],
            [['monday', 'tuesday', 'wensday', 'thursday', 'friday', 'saturday', 'sunday'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'room_id' => 'Room ID',
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
