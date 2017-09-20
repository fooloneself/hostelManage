<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%app_user_relation}}".
 *
 * @property integer $app_user_id
 * @property integer $person_id
 * @property integer $is_binding
 * @property integer $create_time
 */
class AppUserRelation extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_user_relation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_user_id', 'person_id'], 'required'],
            [['app_user_id', 'person_id', 'is_binding', 'create_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_user_id' => 'App User ID',
            'person_id' => 'Person ID',
            'is_binding' => 'Is Binding',
            'create_time' => 'Create Time',
        ];
    }

    /**
     * 获取关联人
     * @param $userId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getRelationPerson($userId){
        return self::find()->alias('aur')
                ->select('p.person_id,p.name,p.activeCode,p.activeStatus,p.ic_cusomer_no,p.numberType,p.number,p.type,p.in_residence,p.class_id,p.school_id')
                ->leftJoin(Persons::tableName().' p','aur.person_id=p.person_id')
                ->where(['aur.app_user_id'=>$userId,'aur.is_binding'=>1])
                ->asArray()->all();
    }
}
