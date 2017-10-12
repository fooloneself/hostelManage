<template>
<div>
    <Button type="primary" @click="turnUrl('basicDictEdit')">新增</Button>
    <div class="mb"></div>
    <Table :columns="columns" :data="data" stripe></Table>
    <div class="mb"></div>
    <Page :total="100" show-total></Page>
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
                        key: 'index'
                    },
                    {
                        title: '字典名称',
                        width: 180,
                        key: 'name'

                    },
                    {
                        title: '唯一代码',
                        width: 100,
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
                                            this.turnUrl('basicDictInfo',{id:params.row.id})
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
                                            this.turnUrl('basicDictInfoEdit',{id:params.row.id})
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
                                            this.turnUrl('basicDictEdit',{id:params.row.id})
                                        }
                                    }
                                }, '编辑'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                data: [
                    {index:1,name:'性别',code:'sex',introduce:'性别',id:'22'},{},{},{},{},{},{},{},{},{}
                ]
            }
        },
        methods:{
            turnUrl:function(url,query){
                this.$router.push(url)
            }
        }
    }
</script>