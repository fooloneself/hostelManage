<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%premises}}".
 *
 * @property integer $id
 * @property integer $mch_id
 * @property integer $create_time
 * @property integer $city
 * @property integer $floor
 * @property double $number
 * @property string $longitude
 * @property string $latitude
 * @property string $name
 * @property string $address
 * @property string $street
 * @property string $introduce
 */
class Premises extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%premises}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id', 'city'], 'required'],
            [['mch_id', 'create_time', 'city', 'floor'], 'integer'],
            [['number', 'longitude', 'latitude'], 'number'],
            [['introduce'], 'string'],
            [['name', 'address', 'street'], 'string', 'max' => 200],
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
            'city' => 'City',
            'floor' => 'Floor',
            'number' => 'Number',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'name' => 'Name',
            'address' => 'Address',
            'street' => 'Street',
            'introduce' => 'Introduce',
        ];
    }
}
