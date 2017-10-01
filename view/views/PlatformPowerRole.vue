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
                        title: '角色名称',
                        width: 180
                    },
                    {
                        title: '有效期限',
                        width: 100
                    },
                    {
                        title: '数据状态',
                        width: 100
                    },
                    {
                        title: '角色说明',
                        width: 600
                    },
                    {
                        title: '操作',
                        key: 'action',
                        fixed: 'right',
                        width: 310,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
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
                                }, '克隆'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '分配账号'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '账号列表')
                            ]);
                        }
                    }
                ],
                data: [
                    {},{},{},{},{},{},{},{},{},{}
                ]
            }
        }
    }
</script>