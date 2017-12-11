<?php
namespace service\guest;
use common\components\Server;
use service\merchant\Merchant;

/**
 * 住客
 * Class Guest
 * @package service\guest
 */
class Guest extends Server{
    protected $guest;

    /**
     * 构造函数
     * Guest constructor.
     * @param \common\models\MerchantMember $guest
     */
    public function __construct(\common\models\MerchantMember $guest)
    {
        $this->guest=$guest;
    }

    /**
     * 获取手机号
     * @return mixed
     */
    public function getMobile(){
        return $this->guest->getAttribute('mobile');
    }

    /**
     * 获取姓名
     * @return mixed
     */
    public function getName(){
        return $this->guest->getAttribute('name');
    }

    /**
     * 获取顾客ID
     * @return int
     */
    public function getId(){
        return intval($this->guest->getAttribute('id'));
    }

    /**
     * 通过手机号、姓名实例化顾客
     * @param Merchant $merchant
     * @param $mobile
     * @param $name
     * @return null|static
     */
    public static function by(Merchant $merchant,$mobile,$name){
        $guest=\common\models\MerchantMember::findOne(['mch_id'=>$merchant->getId(),'mobile'=>$mobile]);
        if(empty($guest)){
            $guest=new \common\models\MerchantMember();
            $guest->mch_id=$merchant->getId();
            $guest->create_time=$_SERVER['REQUEST_TIME'];
            $guest->mobile=$mobile;
            $guest->name=$name;
            if(!$guest->insert()){
                return null;
            }
        }else if($name!=$guest->name){
            $guest->name=$name;
            $guest->update();
        }
        return new static($guest);
    }

    /**
     * 是否生日
     * @return bool
     */
    public function isBirthday(){
        return $this->guest==intval(date('Ymd'));
    }

    /**
     * 是否会员
     */
    public function isMember(){
        return $this->guest->is_member==1? true: false;
    }

    /**
     * 获取会员等级
     * @return int
     */
    public function getMemberRank(){
        return intval($this->guest->rank);
    }
}