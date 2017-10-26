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
                        title: '等级名称',
                        width: 120
                    },
                    {
                        title: '消费区间',
                        width: 150
                    },
                    {
                        title: '积分区间',
                        width: 150
                    },
                    {
                        title: '等级说明'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 150,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/memberRankEdit')
                                        }
                                    }
                                }, '编辑'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '删除订单')
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