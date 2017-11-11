<template>
<div>
    <Row>
        <Col span="24">
            <Alert type="error" show-icon>
                <Icon type="alert-circled" slot="icon"></Icon>
                <template slot="desc">
                    <Button type="error" @click="turnUrl('/admin/memberBlackEdit')">新增黑名单</Button>
                    <span class="icon-ml">加入到黑名单的不良客户会被全网共享，当为黑名单上的客人办理订单时，系统会进行自动提醒。</span>
                </template>
            </Alert>
        </Col>
    </Row>
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
                        key: 'id'
                    },
                    {
                        title: '人员姓名',
                        width: 200,
                        key: 'name'
                    },
                    {
                        title: '证件号',
                        width: 200,
                        key: 'cardid'
                    },
                    {
                        title: '手机号',
                        width: 200,
                        key: 'mobile'
                    },
                    {
                        title: '备注',
                        key: 'mobile'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 120,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/memberBlackEdit')
                                        }
                                    }
                                }, '查看'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.$Modal.confirm({
                                                title:'提示',
                                                content:'请确认是否要将该人员从黑名单移除？'
                                            });
                                        }
                                    }
                                }, '移除')
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