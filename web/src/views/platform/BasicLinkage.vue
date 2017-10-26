<template>
<div>
    <Button @click="turnUrl('/admin/basicLinkageEdit/0')" type="primary">新增</Button>
    <div class="mb"></div>
    <Table :columns="columns" :data="data" stripe></Table>
    <div class="mb"></div>
    <Page :total="totalCount" @on-change="refresh" :page-size="10" show-total></Page>
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
                        key: 'id'
                    },
                    {
                        title: '菜单名称',
                        width: 180,
                        key: 'label'
                    },
                    {
                        title: '唯一代码',
                        width: 100,
                        key: 'code'
                    },
                    {
                        title: '菜单描述',
                        key: 'introduce'
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
                                            this.turnUrl('/admin/basicLinkageChild/'+params.row.code+'/0')
                                        }
                                    }
                                }, '管理子菜单'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/basicLinkageChildEdit/'+params.row.code+'/0/0')
                                        }
                                    }
                                }, '新增子菜单'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/basicLinkageEdit/'+params.row.id)
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
                                                title: '删除',
                                                content: '确定要删除吗？',
                                                onOk (){
                                                    that.delete(params.row.id);
                                                }
                                            })
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                data: [],
                totalCount: 0,
                current:1
            }
        },
        mounted(){
            this.refresh(1)
        },
        methods:{
            turnUrl:function(url){
                this.$router.push(url)
            },
            delete(id){
                this.host.post('linkageMenuDelete',{id: id}).then(function(res){
                    if(res.isSuccess()){
                        this.re
                    }else{
                        this.$Notice.info({
                            title: '提示',
                            desc: res.error()
                        })
                    }
                })
            },
            refresh(page){
                this.current=page;
                var that=this;
                this.host.post('linkageMenuList',{page: page}).then(function(res){
                    if(res.isSuccess()){
                        that.totalCount=res.data().total;
                        that.data=res.data().list;
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