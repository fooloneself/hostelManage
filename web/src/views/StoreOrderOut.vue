<template>
<div>
    <Tabs value="today" type="card">
        <TabPane label="今日" name="today">
            <Table :columns="columns" :data="data" stripe></Table>
            <div class="mb"></div>
            <Page :total="100" show-total></Page>
        </TabPane>
        <TabPane label="明日" name="tomorrow">
            <Table :columns="columns" :data="data" stripe></Table>
            <div class="mb"></div>
            <Page :total="100" show-total></Page>
        </TabPane>
    </Tabs>
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
                        title: '客人来源',
                        width: 120
                    },
                    {
                        title: '入住/退房时间',
                        width: 180
                    },
                    {
                        title: '支付方式',
                        width: 100
                    },
                    {
                        title: '房间类型'
                    },
                    {
                        title: '房号',
                        width: 100
                    },
                    {
                        title: '费用共计',
                        width: 100
                    },
                    {
                        title: '入住人',
                        width: 100
                    },
                    {
                        title: '手机号',
                        width: 100
                    },
                    {
                        title: '操作',
                        key: 'action',
                        fixed: 'right',
                        width: 160,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '查看'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '续住'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '退房')
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