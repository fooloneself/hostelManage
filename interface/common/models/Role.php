<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%role}}".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $mch_can
 * @property string $code
 * @property string $name
 * @property string $mark
 */
class Role extends \common\components\ActiveRecord
{
    const STATUS_DISABLE    =0;
    const STATUS_ENABLE     =1;
    const MCH_CAN_NO        =0;
    const MCH_CAN_YES       =1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'mch_can'], 'integer'],
            [['name'], 'required'],
            [['code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['mark'], 'string', 'max' => 600],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'mch_can' => 'Mch Can',
            'code' => 'Code',
            'name' => 'Name',
            'mark' => 'Mark',
        ];
    }
}
