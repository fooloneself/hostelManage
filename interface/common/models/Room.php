<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%room}}".
 *
 * @property integer $id
 * @property integer $premises_id
 * @property integer $number
 * @property integer $create_time
 * @property integer $mch_id
 * @property integer $bed_num
 * @property integer $floor
 * @property integer $status
 * @property integer $type
 * @property string $blair_said
 * @property string $pic
 * @property string $cover
 * @property string $introduce
 */
class Room extends \common\components\ActiveRecord
{
    const STATUS_CAN_ORDER  =0;//可定
    const STATUS_DIRTY       =1;//脏房
    const STATUS_UN_OPEN    =2;//锁定，不对外开放
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%room}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['premises_id', 'number', 'mch_id'], 'required'],
            [['premises_id', 'number', 'create_time', 'mch_id', 'bed_num', 'status', 'type'], 'integer'],
            [['introduce'], 'string'],
            [['blair_said', 'cover'], 'string', 'max' => 100],
            [['pic'], 'string', 'max' => 400],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'premises_id' => 'Premises ID',
            'number' => 'Number',
            'create_time' => 'Create Time',
            'mch_id' => 'Mch ID',
            'bed_num' => 'Bed Num',
            'status' => 'Status',
            'type' => 'Type',
            'blair_said' => 'Blair Said',
            'pic' => 'Pic',
            'cover' => 'Cover',
            'introduce' => 'Introduce',
        ];
    }
}
