<template>
<div>
    <Button type="primary" @click="turnUrl('/admin/cutdownEdit')">新增</Button>
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
                        title: '满减名称'
                    },
                    {
                        title: '满减规则',
                        width: 240
                    },
                    {
                        title: '适用会员等级',
                        width: 200
                    },
                    {
                        title: '活动可叠加',
                        width: 120
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
                                            this.turnUrl('/admin/cutdownEdit')
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
                                            this.turnUrl('/admin/planEdit')
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
                                            this.$Modal.confirm({
                                                title:'提示',
                                                content:'请确认是否要删除？'
                                            });
                                        }
                                    }
                                }, '删除')
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