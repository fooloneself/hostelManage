<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%channel}}".
 *
 * @property integer $id
 * @property integer $mch_id
 * @property string $commission
 * @property string $name
 * @property string $introduce
 */
class Channel extends \common\components\ActiveRecord
{
    const STATUS_CANCEL   =0;//撤销
    const STATUS_REVERSE  =1;//预定
    const STATUS_OCCUPANCY =2;//入住
    const STATUS_CHECK_OUT =3;//退房
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%channel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id'], 'integer'],
            [['commission'], 'number'],
            [['name'], 'string', 'max' => 100],
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
            'mch_id' => 'Mch ID',
            'commission' => 'Commission',
            'name' => 'Name',
            'introduce' => 'Introduce',
        ];
    }
}
