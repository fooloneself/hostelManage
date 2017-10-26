<?php
namespace service;
use yii\db\ActiveQuery;

class Pager{
    protected $query;
    protected $pageSize;
    protected function __construct(ActiveQuery $query,$pageSize)
    {
        $this->query=$query;
        $this->pageSize=$pageSize;
    }

    /**
     * 获取页面数据列表
     * @param $page
     * @return array
     */
    public function get($page){
        $count=intval($this->query->count());
        if($count<=0){
            $count=0;
            $data=[];
        }else{
            if(($page-1)*$this->pageSize>=$count && $page>1){
                $page-=1;
            }
            $data=$this->query->offset(($page-1)*$this->pageSize)->limit($this->pageSize)->asArray()->all();
        }
        return [$count,$data];
    }

    /**
     * 实例化
     * @param ActiveQuery $query
     * @param int $pageSize
     * @return static
     */
    public static function instance(ActiveQuery $query,$pageSize=10){
        return new static($query,$pageSize);
    }
}