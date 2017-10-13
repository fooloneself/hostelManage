<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%linkage_menu_item}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $order
 * @property string $code
 * @property string $label
 * @property string $introduce
 */
class LinkageMenuItem extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%linkage_menu_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'order'], 'integer'],
            [['code'], 'string', 'max' => 200],
            [['label'], 'string', 'max' => 100],
            [['introduce'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'order' => 'Order',
            'code' => 'Code',
            'label' => 'Label',
            'introduce' => 'Introduce',
        ];
    }
}
