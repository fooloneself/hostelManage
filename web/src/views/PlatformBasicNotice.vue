<template>
<div>
    <Button type="primary" @click="turnUrl('basicNoticeEdit')">新增</Button>
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
                        title: '主题',
                        key: 'title'

                    },
                    {
                        title: '发送状态',
                        width: 180,
                        key: 'status'
                    },
                    {
                        title: '发送时间',
                        width: 180,
                        key: 'date'
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
                                            this.turnUrl('basicNoticeEdit',{id:params.row.id})
                                        }
                                    }
                                }, '编辑'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '发送'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '撤回'),
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
                    {status:'草稿'},{status:'发送'},{status:'撤回'},{},{},{},{},{},{},{}
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