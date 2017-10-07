<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%room_type}}".
 *
 * @property integer $id
 * @property integer $mch_id
 * @property integer $create_time
 * @property integer $allow_hour_room
 * @property string $default_price
 * @property string $hour_room_price
 * @property string $name
 * @property string $introduce
 */
class RoomType extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%room_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id', 'name'], 'required'],
            [['mch_id', 'create_time', 'allow_hour_room'], 'integer'],
            [['default_price', 'hour_room_price'], 'number'],
            [['introduce'], 'string'],
            [['name'], 'string', 'max' => 100],
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
            'allow_hour_room' => 'Allow Hour Room',
            'default_price' => 'Default Price',
            'hour_room_price' => 'Hour Room Price',
            'name' => 'Name',
            'introduce' => 'Introduce',
        ];
    }
}
