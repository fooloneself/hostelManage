<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%room_day_price}}".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $mch_id
 * @property integer $year
 * @property integer $date
 * @property integer $month
 * @property integer $day
 * @property integer $week
 * @property string $price
 */
class RoomDayPrice extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%room_day_price}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'mch_id', 'year', 'date', 'month', 'day', 'week'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'mch_id' => 'Mch ID',
            'year' => 'Year',
            'date' => 'Date',
            'month' => 'Month',
            'day' => 'Day',
            'week' => 'Week',
            'price' => 'Price',
        ];
    }

    /**
     * 获取单日价格列表
     * @param $mchId
     * @param $typeId
     * @param $startTime
     * @param $endTime
     * @return array
     */
    public static function getDayPriceList($mchId,$typeId,$startTime,$endTime){
        $start=intval(date('Y-m-d',$startTime));
        $end=intval(date('Y-m-d',$endTime));
        return self::find()
            ->select('week,price')
            ->where(['mch_id'=>$mchId,'type_id'=>$typeId])
            ->andWhere(['between','date',$start,$end])
            ->andWhere('date >=:start and date <:end',[':start'=>$start,':end'=>$end])
            ->asArray()->all();
    }
}
