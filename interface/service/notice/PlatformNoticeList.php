<?php
namespace service\notice;
use common\models\Notice;

class PlatformNoticeList extends NoticeListAbstract{

    protected function buildQuery()
    {
        $query=Notice::find();
        return $query->select('id,title,status,public_time')->where(['admin_id'=>$this->adminId])->orderBy('create_time desc');
    }

    protected function handleRecord($item)
    {
        $record=[];
        $record['id']=$item['id'];
        $record['title']=$item['title'];
        $record['publicDate']=($item['public_time']>0)?date('Y-m-d H:i:s',$item['public_time']):'';
        $record['status']=Notice::$status[$item['status']];
        return $record;
    }
}