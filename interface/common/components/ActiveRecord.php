<?php
namespace common\components;
class ActiveRecord extends \yii\db\ActiveRecord
{
    public function test()
    {
        return 333;
    }
}