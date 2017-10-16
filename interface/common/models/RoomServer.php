<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%room_server}}".
 *
 * @property integer $room_id
 * @property string $dictionary_key
 */
class RoomServer extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%room_server}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['room_id', 'dictionary_key'], 'required'],
            [['room_id'], 'integer'],
            [['dictionary_key'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'room_id' => 'Room ID',
            'dictionary_key' => 'Dictionary Key',
        ];
    }
}
