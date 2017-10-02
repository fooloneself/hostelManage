<template>
<div>
    <Form inline>
        <Row>
            <Col span="6">
                <FormItem>
                    <Button type="primary">新增</Button>
                </FormItem>
            </Col>
            <Col span="18" style="text-align: right;">
                <FormItem>
                    <Select placeholder="会员等级" style="text-align: left;">
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
            </Col>
        </Row>
    </Form>
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
                        title: '人员姓名',
                        width: 100
                    },
                    {
                        title: '手机号',
                        width: 180
                    },
                    {
                        title: '微信号',
                        width: 100
                    },
                    {
                        title: '生日',
                        width: 100
                    },
                    {
                        title: '会员等级',
                        width: 100
                    },
                    {
                        title: '消费金额',
                        width: 100
                    },
                    {
                        title: '积分',
                        width: 100
                    },
                    {
                        title: '注册时间',
                        width: 100
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
        }
    }
</script>