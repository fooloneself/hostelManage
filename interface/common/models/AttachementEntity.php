<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%attachement_entity}}".
 *
 * @property integer $id
 * @property string $save_name
 * @property string $name
 * @property string $path
 * @property integer $size
 * @property integer $type
 * @property string $ext
 * @property string $hash
 * @property integer $create_time
 */
class AttachementEntity extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attachement_entity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['save_name', 'path', 'ext', 'hash'], 'required'],
            [['size', 'type', 'create_time'], 'integer'],
            [['save_name'], 'string', 'max' => 200],
            [['name', 'hash'], 'string', 'max' => 100],
            [['path'], 'string', 'max' => 500],
            [['ext'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'save_name' => 'Save Name',
            'name' => 'Name',
            'path' => 'Path',
            'size' => 'Size',
            'type' => 'Type',
            'ext' => 'Ext',
            'hash' => 'Hash',
            'create_time' => 'Create Time',
        ];
    }
}
