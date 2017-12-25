<?php
namespace service\order\bill;
use service\order\activity\Activity;
class RoomBillGenerator{
    private static $_instance;
    protected $activity;
    protected function __construct(Activity $activity=null)
    {
        $this->activity=$activity;
    }

    public static function instance(Activity $activity=null){
        if(self::$_instance===null){
            self::$_instance=new static($activity);
        }
        return self::$_instance;
    }
}