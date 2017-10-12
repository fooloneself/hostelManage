<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%member}}".
 *
 * @property integer $id
 * @property integer $create_time
 * @property integer $birthday
 * @property integer $sex
 * @property integer $number_type
 * @property string $consumption_amount
 * @property string $name
 * @property string $mobile
 * @property string $number
 * @property string $wx_account
 * @property string $mark
 */
class Member extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'birthday', 'sex', 'number_type'], 'integer'],
            [['consumption_amount'], 'number'],
            [['mark'], 'string'],
            [['name', 'number'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 50],
            [['wx_account'], 'string', 'max' => 200],
            [['number', 'number_type'], 'unique', 'targetAttribute' => ['number', 'number_type'], 'message' => 'The combination of Number Type and Number has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'create_time' => 'Create Time',
            'birthday' => 'Birthday',
            'sex' => 'Sex',
            'number_type' => 'Number Type',
            'consumption_amount' => 'Consumption Amount',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'number' => 'Number',
            'wx_account' => 'Wx Account',
            'mark' => 'Mark',
        ];
    }
}
