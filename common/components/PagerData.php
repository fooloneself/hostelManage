<?php
namespace common\components;
class PagerData{
    public $data=[];
    public $totalCount=0;
    public function __construct($totalCount,$data)
    {
        $this->totalCount=$totalCount;
        $this->data=$data;
    }

    public function getTotalCount(){
        return $this->totalCount;
    }

    public function getList(){
        return $this->data;
    }
}