<?php
namespace service\push;
use common\models\AppUserAuth;
use common\models\AppUserRelation;

class PersonData extends UserDataAbstract {
    public $personId;
    public function __construct($personId)
    {
        $this->personId=$personId;
    }

    public function getPushUsers(){
        $appUserId=AppUserRelation::getUserIdOfPerson($this->personId);
        if($appUserId==0){
            return [];
        }
        $appUsers=AppUserAuth::getUsersAuthWith($appUserId);
        array_push($appUsers,$appUserId);
        return array_unique($appUsers);
    }
}