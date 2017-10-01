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
                        title: '房间类型',
                        width: 180
                    },
                    {
                        title: '默认价格',
                        width: 100
                    },
                    {
                        title: '房号',
                        width: 100
                    },
                    {
                        title: '锁房状态',
                        width: 100
                    },
                    {
                        title: '房间配套'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 160,
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
                                }, '锁房')
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