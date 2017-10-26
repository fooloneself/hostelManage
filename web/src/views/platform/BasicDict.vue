<template>
<div>
    <Button type="primary" @click="turnUrl('/admin/basicDictEdit/0')">新增</Button>
    <div class="mb"></div>
    <Table :columns="columns" :data="data" stripe></Table>
    <div class="mb"></div>
    <Page :total="totalCount" @on-change="pageTo" :page-size="1" show-total></Page>
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
                        title: '字典名称',
                        width: 160,
                        key: 'label'

                    },
                    {
                        title: '唯一代码',
                        width: 180,
                        key: 'code'
                    },
                    {
                        title: '字典说明',
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
                                            this.turnUrl('/admin/basicDictInfo/'+params.row.code)
                                        }
                                    }
                                }, '管理数据'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/basicDictInfoEdit/'+params.row.code+'/0')
                                        }
                                    }
                                }, '添加数据'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/basicDictEdit/'+params.row.id)
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
                                                content: '确定要删除吗',
                                                onOk (){
                                                    that.delete(params.row.code);
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
                current: 1
            }
        },
        mounted (){
            this.refresh();
        },
        methods:{
            turnUrl:function(url){
                this.$router.push(url)
            },
            pageTo(page){
                this.current=page;
                this.refresh();
            },
            delete:function(code){
                var that=this;
                this.host.post('dictionaryDelete',{code: code}).then(function(res){
                    if(res.isSuccess()){
                        that.refresh();
                    }else{
                        this.$Notice.info({
                            title: '提示',
                            desc: res.error()
                        });
                    }
                })
            },
            refresh (){
                var that=this;
                this.host.post('dictionaries',{page :this.current}).then(function(res){
                    if(res.isSuccess()){
                        that.data=res.data().list;
                        that.totalCount=parseInt(res.data().total);
                    }
                })
            }
        }
    }
</script>