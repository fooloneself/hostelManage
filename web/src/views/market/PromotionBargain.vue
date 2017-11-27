<template>
<div>
    <Button type="primary" @click="turnUrl('/admin/bargainEdit/0')">新增</Button>
    <div class="mb"></div>
    <Table :columns="columns" :data="data" stripe></Table>
    <div class="mb"></div>
    <Page :total="totalCount" :current-page="filter.page" :page-size="filter.pageSize" @on-change="pageTo" show-total></Page>
</div>
</template>
<script>
    export default {
        data () {
            return {
                columns: [
                    {
                        title: '序号',
                        width: 60,
                        type:'index'
                    },
                    {
                        title: '特价名称',
                        key:'name'
                    },
                    {
                        title: '优惠价',
                        width: 200,
                        key:'discount'
                    },
                    {
                        title: '适用会员等级',
                        width: 200,
                        key:'memberRank'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 210,
                        fixed: 'right',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/bargainEdit/'+params.row.id)
                                        }
                                    }
                                }, '编辑'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/planEdit/'+params.row.id)
                                        }
                                    }
                                }, '活动执行计划'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            var that=this;
                                            this.$Modal.confirm({
                                                title:'提示',
                                                content:'请确认是否要删除？',
                                                onOk(){
                                                    that.del(params.row.id);
                                                }
                                            });
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                data: [],
                totalCount:0,
                filter:{
                    page:1,
                    pageSize:10
                }
            }
        },
        mounted(){
            this.refresh();
        },
        methods:{
            turnUrl:function(url,query){
                this.$router.push(url)
            },
            refresh(){
                var that=this;
                this.host.post('merchantActivitySpecialPriceList',this.filter).then(function(res){
                    if(res.isSuccess()){
                        that.data=res.data().list;
                        that.totalCount=res.data().totalCount;
                    }else{
                        this.$Notice.info({
                            title:'错误提示',
                            desc: res.error()
                        })
                    }
                })
            },
            pageTo(page){
                this.filter.page=page;
                this.refresh()
            },
            del(id){
                var that=this;
                this.host.post('merchantActivityDel',{id:id}).then(function(res){
                    if(res.isSuccess()){
                        that.refresh();
                    }else{
                        this.$Notice.info({
                            title:'错误提示',
                            desc: res.error()
                        })
                    }
                })
            }
        }
    }
</script>