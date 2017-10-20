<?php
namespace service\notice;
use common\models\Notice;
use common\models\NoticeRead;

class MchNoticeList extends NoticeListAbstract {
    /**
     * @return \yii\db\ActiveQuery
     */
    protected function buildQuery(){
        $query=Notice::find();
        $query->alias('n')
            ->select('n.title,n.id,n.public_time,nr.read_time')
            ->leftJoin(NoticeRead::tableName().' nr','n.id=nr.notice_id and nr.admin_id=:adminId',[':adminId'=>$this->adminId])
            ->where(['n.status'=>Notice::STATUS_PUBLIC])
            ->orderBy('n.public_time desc');
        return $query;
    }

    /**
     * 处理单条处理
     * @param $item
     * @return array
     */
    protected function handleRecord($item){
        $record=[];
        $record['id']=$item['id'];
        $record['title']=$item['title'];
        $record['hasRead']=$item['read_time']>0? '已读': '未读';
        $record['publicDate']=date('Y-m-d H:i:s',$item['public_time']);
        return $record;
    }
}