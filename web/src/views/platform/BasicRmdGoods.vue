<template>
<div>
    <Button @click="turnUrl('/admin/basicRmdGoodEdit')" type="primary">新增</Button>
    <div class="mb"></div>
    <Table :columns="columns" :data="data" stripe></Table>
    <div class="mb"></div>
    <Page :total="totalCount" :current="page" :page-size="pageSize" show-total></Page>
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
                        type: 'index'
                    },
                    {
                        title: '商品类型',
                        width: 180,
                        key: 'type'
                    },
                    {
                        title: '商品标题',
                        key: 'title'
                    },
                    {
                        title: '商品价格',
                        width: 100,
                        key: 'price'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        fixed: 'right',
                        width: 260,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                        }
                                    }
                                }, '上架'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                        }
                                    }
                                }, '下架'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/basicRmdGoodEdit')
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
                                                title: '提示',
                                                content: '确定要删除吗',
                                                onOk (){}
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
                page: 1,
                pageSize: 10
            }
        },
        methods:{
            turnUrl:function(url,query){
                this.$router.push(url)
            }
        }
    }
</script>