<template>
<div>
    <Button type="primary" @click="turnUrl('basicDictEdit/0')">新增</Button>
    <div class="mb"></div>
    <Table :columns="columns" :data="data" stripe></Table>
    <div class="mb"></div>
    <Page :total="totalCount" show-total></Page>
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
                                            this.turnUrl('basicDictInfo/'+params.row.code)
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
                                            this.turnUrl('/basicDictInfoEdit/'+params.row.code+'/0')
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
                                            this.turnUrl('basicDictEdit/'+params.row.id)
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
                                            var res=confirm('确定要删除吗？');
                                            if(res){
                                                this.delete(params.row.code);
                                            }
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                data: [],
                totalCount: 0
            }
        },
        mounted (){
            var that=this;
            this.host.post('dictionaries').then(function(res){
                if(res.isSuccess()){
                    that.data=res.data().list;
                    that.totalCount=parseInt(res.data().total);
                }
            })
        },
        methods:{
            turnUrl:function(url){
                this.$router.push(url)
            },
            delete:function(code){
                this.host.post('dictionaryDelete',{code: code}).then(function(res){
                    if(res.isSuccess()){
                        location.reload();
                    }else{
                        this.$Notice.info({
                            title: '提示',
                            desc: res.error()
                        });
                    }
                })
            }
        }
    }
</script>