<template>
<div>
    <Button type="primary" @click="turnUrl('/admin/basicNoticeEdit/0')">新增</Button>
    <div class="mb"></div>
    <Table :columns="columns" :data="data" stripe></Table>
    <div class="mb"></div>
    <Page :total="totalCount" @on-change="pageTo" :page-size="10" show-total></Page>
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
                        title: '主题',
                        key: 'title'

                    },
                    {
                        title: '状态',
                        width: 180,
                        key: 'status'
                    },
                    {
                        title: '发送时间',
                        width: 180,
                        key: 'publicDate'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 280,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/basicNoticeEdit/'+params.row.id)
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
                                            var res=confirm('确定要发布吗？');
                                            if(res){
                                                this.public(params)
                                            }
                                        }
                                    }
                                }, '发送'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            var res=confirm('确定要撤回吗？');
                                            if(res){
                                                this.revoke(params)
                                            }
                                        }
                                    }
                                }, '撤回'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            var res=confirm('确定要删除吗？');
                                            if(res){
                                                this.delete(params)
                                            }
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                data: [],
                totalCount: 0,
                current: 1
            }
        },
        mounted (){
            this.refresh();
        },
        methods:{
            turnUrl:function(url,query){
                this.$router.push(url)
            },
            public (param){
                this.host.post('platformNoticePublic',{id:param.row.id}).then(function(res){
                    if(res.isSuccess()){
                        param.row.status='发布';
                    }else{
                        this.$Notice.info({
                            title: '提示',
                            desc: res.error()
                        })
                    }
                })
            },
            revoke (param){
                this.host.post('platformNoticeRevoke',{id:param.row.id}).then(function(res){
                    if(res.isSuccess()){
                        param.row.status='撤回';
                    }else{
                        this.$Notice.info({
                            title: '提示',
                            desc: res.error()
                        })
                    }
                })
            },
            delete (param){
                this.host.post('platformNoticeDelete',{id:param.row.id}).then(function(res){
                    if(res.isSuccess()){
                        location.reload();
                    }else{
                        this.$Notice.info({
                            title: '提示',
                            desc: res.error()
                        })
                    }
                })
            },
            pageTo (page){
                this.current=page;
                this.refresh();
            },
            refresh (){
                var that=this;
                this.host.post('platformNoticeList',{page: this.current}).then(function(res){
                    if(res.isSuccess()){
                        that.data=res.data().list;
                        that.totalCount=res.data().totalCount;
                    }else{
                        that.$Notice.info({
                            title: '提示',
                            desc: res.error()
                        })
                    }
                })
            }
        }
    }
</script>