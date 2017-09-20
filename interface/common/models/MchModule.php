<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%mch_module}}".
 *
 * @property integer $mch_id
 * @property string $module_code
 */
class MchModule extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mch_module}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id', 'module_code'], 'required'],
            [['mch_id'], 'integer'],
            [['module_code'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mch_id' => 'Mch ID',
            'module_code' => 'Module Code',
        ];
    }

    /**
     * 获取商户可访问的所有模块
     * @param $mchId
     * @return array
     */
    public static function allModuleOfMerchant($mchId){
        return self::find()
            ->select('module_code')
            ->where(['mch_id'=>$mchId])
            ->column();
    }
}
