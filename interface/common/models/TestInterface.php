<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%test_interface}}".
 *
 * @property integer $id
 * @property string $url
 * @property string $version
 */
class TestInterface extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%test_interface}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'version'], 'required'],
            [['url'], 'string', 'max' => 200],
            [['version'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'version' => 'Version',
        ];
    }
}
