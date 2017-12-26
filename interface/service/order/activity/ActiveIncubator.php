<?php
namespace service\order\activity;
use service\merchant\Merchant;
use common\models\MerchantActivity;
use common\components\Server;
class ActiveIncubator extends Server{
    protected $merchant;
    private static $_incubator;

    protected function __construct(Merchant $merchant)
    {
        $this->merchant=$merchant;
    }

    public static function incubator(Merchant $merchant){
        if(self::$_incubator===null){
            self::$_incubator=new self($merchant);
        }
        return self::$_incubator;
    }
    /**
     * 获取活动
     * @param $activityId
     * @return bool|DiscountActivity|FullCutActivity|SpecialActivity
     */
    public function get($activityId){
        $activity=MerchantActivity::findOne(['id'=>$activityId,'mch_id'=>$this->merchant->getId()]);
        if(empty($activity)){
            $this->setError(ErrorManager::ERROR_ACTIVITY_NOT_FOUND);
            return false;
        }else{
            switch ($activity->type){
                case MerchantActivity::TYPE_DISCOUNT:
                    return new DiscountActivity($this->merchant,$activity);
                case MerchantActivity::TYPE_FULL_CUT:
                    return new FullCutActivity($this->merchant,$activity);
                case MerchantActivity::TYPE_SPECIAL_OFFER:
                    return new SpecialActivity($this->merchant,$activity);
                default:
                    $this->setError(ErrorManager::ERROR_ACTIVITY_WRONG);
                    return false;
            }
        }
    }
}