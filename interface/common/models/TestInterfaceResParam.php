<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%test_interface_res_param}}".
 *
 * @property integer $id
 * @property string $type
 * @property string $name
 * @property string $label
 * @property integer $interface_id
 * @property integer $pid
 */
class TestInterfaceResParam extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%test_interface_res_param}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name', 'interface_id', 'pid'], 'required'],
            [['interface_id', 'pid'], 'integer'],
            [['type'], 'string', 'max' => 100],
            [['name', 'label'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'name' => 'Name',
            'label' => 'Label',
            'interface_id' => 'Interface ID',
            'pid' => 'Pid',
        ];
    }
}
