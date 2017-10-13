<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%member_rank_divide}}".
 *
 * @property string $id
 * @property integer $mch_id
 * @property integer $create_time
 * @property integer $min_integral
 * @property string $min_consumption_amount
 * @property string $name
 * @property string $mark
 */
class MemberRankDivide extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_rank_divide}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id', 'name'], 'required'],
            [['mch_id', 'create_time', 'min_integral'], 'integer'],
            [['min_consumption_amount'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['mark'], 'string', 'max' => 1000],
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
            'min_integral' => 'Min Integral',
            'min_consumption_amount' => 'Min Consumption Amount',
            'name' => 'Name',
            'mark' => 'Mark',
        ];
    }
}
