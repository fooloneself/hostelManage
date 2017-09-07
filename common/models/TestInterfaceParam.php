<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%test_interface_param}}".
 *
 * @property integer $id
 * @property string $type
 * @property string $label
 * @property string $name
 * @property integer $required
 * @property integer $interface_id
 * @property string $description
 */
class TestInterfaceParam extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%test_interface_param}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'label', 'name', 'interface_id'], 'required'],
            [['required', 'interface_id'], 'integer'],
            [['type'], 'string', 'max' => 50],
            [['label', 'name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 200],
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
            'label' => 'Label',
            'name' => 'Name',
            'required' => 'Required',
            'interface_id' => 'Interface ID',
            'description' => 'Description',
        ];
    }
}
