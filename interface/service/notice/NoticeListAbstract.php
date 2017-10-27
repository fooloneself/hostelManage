<?php
namespace service\notice;
use service\Pager;

abstract class NoticeListAbstract{
    public $page;
    public $pageSize;
    public $adminId;

    /**
     * 获取列表
     * @return array
     */
    public function get(){
        $query=$this->buildQuery();
        list($count,$list)=Pager::instance($query,$this->pageSize)->get($this->page);
        $list=$this->handleList($list);
        return [$count,$list];
    }

    /**
     * 处理列表数据
     * @param $list
     * @return array
     */
    protected function handleList($list){
        $record=[];
        foreach ($list as $item){
            $record[]=$this->handleRecord($item);
        }
        return $record;
    }

    /**
     * 构建数据库查询器
     * @return mixed
     */
    abstract protected function buildQuery();

    /**
     * 处理单条数据
     * @param $item
     * @return mixed
     */
    abstract protected function handleRecord($item);
}