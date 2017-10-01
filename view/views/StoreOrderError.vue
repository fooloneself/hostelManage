<template>
<div>
    <Form inline>
        <FormItem>
            <Select placeholder="异常原因">
                <Option value="1">未退房</Option>
                <Option value="2">未入住</Option>
                <Option value="3">房费少额</Option>
                <Option value="4">房费多额</Option>
            </Select>
        </FormItem>
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
                        title: '客人来源',
                        width: 100
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
                        title: '房间类型',
                        width: 100
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
                        width: 60,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '查看')
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