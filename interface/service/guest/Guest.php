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
     * @param Guest $guest
     */
    public function __construct(\common\models\Guest $guest)
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
        return $this->guest->getAttribute('person_name');
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
        $guest=\common\models\Guest::findOne(['mch_id'=>$merchant->getId(),'mobile'=>$mobile,'person_name'=>$name]);
        if(empty($guest)){
            $guest=new \common\models\Guest();
            $guest->mch_id=$merchant->getId();
            $guest->create_time=$_SERVER['REQUEST_TIME'];
            $guest->mobile=$mobile;
            $guest->person_name=$name;
            if(!$guest->insert()){
                return null;
            }
        }
        return new static($guest);
    }
}