<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%linkage_menu}}".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $pid
 * @property string $label
 */
class LinkageMenu extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%linkage_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'label'], 'required'],
            [['type', 'pid'], 'integer'],
            [['label'], 'string', 'max' => 100],
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
            'pid' => 'Pid',
            'label' => 'Label',
        ];
    }
}
