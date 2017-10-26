<template>
<div>
    <Button type="primary">新增</Button>
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
                        width: 60
                    },
                    {
                        title: '登录账号'
                    },
                    {
                        title: '姓名',
                        width: 200
                    },
                    {
                        title: '分配角色',
                        width: 200
                    },
                    {
                        title: '数据状态',
                        width: 200
                    },
                    {
                        title: '操作',
                        key: 'action',
                        fixed: 'right',
                        width: 240,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/configAccountEdit')
                                        }
                                    }
                                }, '编辑'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '删除'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '停用'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '重置密码')
                            ]);
                        }
                    }
                ],
                data: [
                    {},{},{},{},{},{},{},{},{},{}
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