<template>
<div>
    <Row>
        <Col span="6">
            <Button type="primary">新增</Button>
        </Col>
        <Col span="18" class="tr">
            <Form inline>
                <FormItem>
                    <Select placeholder="会员等级" class="tl">
                        <Option value="1">普通</Option>
                        <Option value="2">黄金</Option>
                        <Option value="3">铂金</Option>
                        <Option value="4">钻石</Option>
                    </Select>
                </FormItem>
                <FormItem>
                    <Input placeholder="姓名/电话"></Input>
                </FormItem>
                <FormItem>
                    <Button type="primary">查询</Button>
                </FormItem>
            </Form>
        </Col>
    </Row>
    <Row>
        <Col span="24">
            <Table :columns="columns" :data="data" stripe></Table>
            <div class="mb"></div>
            <Page :total="100" show-total></Page>
        </Col>
    </Row>
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
                        title: '人员姓名'
                    },
                    {
                        title: '手机号',
                        width: 180
                    },
                    {
                        title: '微信号',
                        width: 120
                    },
                    {
                        title: '生日',
                        width: 120
                    },
                    {
                        title: '会员等级',
                        width: 120
                    },
                    {
                        title: '消费金额',
                        width: 120
                    },
                    {
                        title: '积分',
                        width: 120
                    },
                    {
                        title: '注册时间',
                        width: 120
                    },
                    {
                        title: '操作',
                        key: 'action',
                        fixed: 'right',
                        width: 180,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('memberListEdit')
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
                                }, '消费记录')
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