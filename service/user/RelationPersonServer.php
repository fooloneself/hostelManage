<?php
namespace service\user;
use common\components\BaseServer;

class RelationPersonServer extends BaseServer{
    //用户ID
    private $_userId;

    //用户已绑卡列表
    private $_relationPerson;
    //关联的孩子
    private $_children=[];
    //自己
    private $_self=[];

    public function __construct($userId)
    {
        $this->_userId=$userId;
        $this->refresh();
    }

    /**
     * 更新数据
     */
    public function refresh(){
        $this->refreshRelationPerson();
        $this->handleRelationPerson();
    }
    /**
     * 获取关联联系人
     * @return array|\yii\db\ActiveRecord[]
     */
    protected function refreshRelationPerson(){
        $this->_relationPerson=AppUserRelation::getRelationPerson($this->_userId);
        return $this;
    }

    /**
     * 处理关联的人员
     */
    private function handleRelationPerson(){
        foreach ($this->_relationPerson as $person){
            if($person['type']==1){
                $this->_children[]=$person;
            }else{
                $this->_self=$person;
            }
        }
    }

    /**
     * 获取绑定的孩子
     * @return mixed
     */
    public function getChildren(){
        return $this->_children;
    }

    /**
     * 获取自己校园信息
     * @return array
     */
    public function getSelf(){
        return $this->_self;
    }

    /**
     * 是否是家长
     * @return bool
     */
    public function isParent(){
        return empty($this->_self);
    }

    /**
     * 判断是否是教师
     * @return bool
     */
    public function isTeacher(){
        if(!empty($this->_self) && $this->_self['type']==2){
            return true;
        }else{
            return false;
        }
    }
}