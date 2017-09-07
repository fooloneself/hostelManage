<?php
namespace service\push;
use common\models\AppUserAuth;
use common\models\AppUserRelation;

class SchoolData extends UserDataAbstract {
    public $schoolId;
    public function __construct($schoolId)
    {
        $this->schoolId=$schoolId;
    }

    public function getPushUsers()
    {
        $userIds=AppUserAuth::getUsersAuthWithUserOfSchool($this->schoolId);
        $userIds=array_merge($userIds,AppUserRelation::getUserIdOfSchool($this->schoolId));
        return array_unique($userIds);
    }
}