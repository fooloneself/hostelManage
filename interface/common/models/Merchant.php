<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%merchant}}".
 *
 * @property integer $id
 * @property integer $city
 * @property integer $create_time
 * @property integer $status
 * @property integer $type
 * @property string $contact_name
 * @property string $name
 * @property string $mobile
 * @property string $telephone
 * @property string $address
 * @property string $introduce
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
            [['city'], 'required'],
            [['city', 'create_time', 'status', 'type'], 'integer'],
            [['introduce'], 'string'],
            [['contact_name'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 200],
            [['mobile', 'telephone'], 'string', 'max' => 50],
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
            'city' => 'City',
            'create_time' => 'Create Time',
            'status' => 'Status',
            'type' => 'Type',
            'contact_name' => 'Contact Name',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'telephone' => 'Telephone',
            'address' => 'Address',
            'introduce' => 'Introduce',
        ];
    }
}
