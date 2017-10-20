<?php
namespace service\notice;
abstract class NoticeListAbstract{
    public $page;
    public $pageSize;
    public $adminId;
    /**
     * 获取偏移量
     * @return mixed
     */
    protected function getOffset(){
        return ($this->page-1)*$this->pageSize;
    }

    /**
     * 获取列表
     * @return array
     */
    public function get(){
        $query=$this->buildQuery();
        $count=intval($query->count());
        $list=$query->offset($this->getOffset())->limit($this->pageSize)->asArray()->all();
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