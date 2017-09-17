<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%merchant}}".
 *
 * @property integer $id
 * @property integer $number
 * @property integer $province
 * @property integer $city
 * @property integer $status
 * @property integer $type
 * @property string $name
 * @property string $address
 */
class Merchant extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%merchant}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'province', 'city'], 'required'],
            [['number', 'province', 'city', 'status', 'type'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['address'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'province' => 'Province',
            'city' => 'City',
            'status' => 'Status',
            'type' => 'Type',
            'name' => 'Name',
            'address' => 'Address',
        ];
    }
}
