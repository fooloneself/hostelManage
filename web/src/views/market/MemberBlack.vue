<template>
<div>
    <Row>
        <Col span="24">
            <Alert type="error" show-icon>
                <Icon type="alert-circled" slot="icon"></Icon>
                <template slot="desc">
                    <Button type="error" @click="turnUrl('/admin/memberBlackEdit/0')">新增黑名单</Button>
                    <span class="icon-ml">加入到黑名单的不良客户会被全网共享，当为黑名单上的客人办理订单时，系统会进行自动提醒。</span>
                </template>
            </Alert>
        </Col>
    </Row>
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
                        type: 'index'
                    },
                    {
                        title: '人员姓名',
                        width: 200,
                        key: 'name'
                    },
                    {
                        title: '证件号',
                        width: 200,
                        key: 'number'
                    },
                    {
                        title: '手机号',
                        width: 200,
                        key: 'mobile'
                    },
                    {
                        title: '备注',
                        key: 'mark'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 120,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/memberBlackEdit/'+params.row.id)
                                        }
                                    }
                                }, '查看'),
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
                                                content:'请确认是否要将该人员从黑名单移除？',
                                                onOk(){
                                                    that.removeFromBlack(params.row.id);
                                                }
                                            });
                                        }
                                    }
                                }, '移除')
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
            pageTo(page){
                this.filter.page=page;
                this.refresh();
            },
            refresh(){
                var that=this;
                this.host.post('merchantMemberBlackList',this.filter).then(function(res){
                    if(res.isSuccess()){
                        that.data=res.data().list;
                        that.totalCount=res.data().totalCount;
                    }else{
                        that.$Notice.info({
                            title:'提示',
                            desc:res.error()
                        })
                    }
                })
            },
            removeFromBlack(id){
                var that=this;
                this.host.post('merchantMemberBlackRemove',{id:id}).then(function(res){
                    if(res.isSuccess()){
                        that.refresh();
                    }else{
                        that.$Notice.info({
                            title:'提示',
                            desc:res.error()
                        })
                    }
                })
            }
        }
    }
</script>