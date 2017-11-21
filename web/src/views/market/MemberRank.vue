<template>
<div>
    <Button @click="turnUrl('/admin/memberRankEdit/0')" type="primary">新增</Button>
    <div class="mb"></div>
    <Table :columns="columns" :data="data" stripe></Table>
    <div class="mb"></div>
    <Page :total="totalCount" :current-page="page" :page-size="pageSize" @on-change="" show-total></Page>
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
                        type: 'index'
                    },
                    {
                        title: '等级名称',
                        width: 120,
                        key: 'name'
                    },
                    {
                        title: '消费金额满',
                        width: 150,
                        key: 'min_consumption_amount'
                    },
                    {
                        title: '积分满',
                        width: 150,
                        key: 'min_integral'
                    },
                    {
                        title: '等级说明',
                        key:'mark'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 200,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/memberRankEdit/'+params.row.id)
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
                                            var that=this;
                                            this.$Modal.confirm({
                                                title:'提示',
                                                content:'会员等级删除以后，该等级下的会员会自动匹配至他满足条件的等级，请确认是否要删除？',
                                                onOk(){
                                                    that.delete(params.row.id)
                                                }
                                            });
                                        }
                                    }
                                }, '删除'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/memberRankBirthday')
                                        }
                                    }
                                }, '生日福利')
                            ]);
                        }
                    }
                ],
                data: [],
                totalCount: 0,
                page:1,
                pageSize:10
            }
        },
        mounted (){
            this.refresh();
        },
        methods:{
            turnUrl:function(url,query){
                this.$router.push(url)
            },
            refresh(){
                var that=this;
                this.host.post('merchantMemberRanks',{page: this.page,pageSize:this.pageSize}).then(function(res){
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
            delete (id){
                var that=this;
                this.host.post('merchantMemberRankDel',{id:id}).then(function(res){
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