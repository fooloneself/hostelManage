<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%linkage_menu}}".
 *
 * @property integer $id
 * @property string $label
 * @property string $code
 * @property string $introduce
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
            [['introduce'], 'string'],
            [['label', 'code'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Label',
            'code' => 'Code',
            'introduce' => 'Introduce',
        ];
    }
}
