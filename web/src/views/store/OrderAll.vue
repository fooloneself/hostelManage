<template>
<div>
    <Form inline>
        <FormItem>
            <Select placeholder="客人来源">
                <Option value="1">线下</Option>
                <Option value="2">携程</Option>
                <Option value="3">美团</Option>
                <Option value="4">艺龙</Option>
            </Select>
        </FormItem>
        <FormItem>
            <DatePicker type="date" placeholder="选择查询日期"></DatePicker>
        </FormItem>
        <FormItem>
            <Input placeholder="房号/姓名/电话"></Input>
        </FormItem>
        <FormItem>
            <Button type="primary">查询</Button>
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
                        width: 180
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
                        width: 110
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 100,
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
        },
        methods:{
            turnUrl:function(url,query){
                this.$router.push(url)
            }
        }
    }
</script>