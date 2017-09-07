<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%persons}}".
 *
 * @property integer $person_id
 * @property string $name
 * @property integer $sex
 * @property string $activeCode
 * @property integer $activeStatus
 * @property integer $activeTime
 * @property string $ic_customer_no
 * @property string $numberType
 * @property string $number
 * @property integer $type
 * @property integer $in_residence
 * @property string $job
 * @property integer $class_id
 * @property integer $school_id
 * @property integer $create_time
 * @property integer $update_time
 */
class Persons extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%persons}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sex', 'activeStatus', 'activeTime', 'type', 'in_residence', 'class_id', 'school_id', 'create_time', 'update_time'], 'integer'],
            [['activeCode'], 'required'],
            [['name', 'ic_customer_no'], 'string', 'max' => 32],
            [['activeCode', 'numberType'], 'string', 'max' => 8],
            [['number'], 'string', 'max' => 64],
            [['job'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'person_id' => 'Person ID',
            'name' => 'Name',
            'sex' => 'Sex',
            'activeCode' => 'Active Code',
            'activeStatus' => 'Active Status',
            'activeTime' => 'Active Time',
            'ic_customer_no' => 'Ic Customer No',
            'numberType' => 'Number Type',
            'number' => 'Number',
            'type' => 'Type',
            'in_residence' => 'In Residence',
            'job' => 'Job',
            'class_id' => 'Class ID',
            'school_id' => 'School ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
