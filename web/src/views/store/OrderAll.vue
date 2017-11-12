<template>
<div>
    <Form inline class="fr">
        <FormItem>
            <DatePicker type="date" placeholder="入住日期"></DatePicker>
        </FormItem>
        <FormItem>
            <Select placeholder="客人来源" style="width: 100px;">
                <Option value="">全部</Option>
                <Option value="1">线下</Option>
                <Option value="2">携程</Option>
                <Option value="3">美团</Option>
                <Option value="4">艺龙</Option>
            </Select>
        </FormItem>
        <FormItem>
            <Select placeholder="房屋类型" style="width: 100px;">
                <Option value="">全部</Option>
                <Option value="房型一">房型一</Option>
                <Option value="房型二">房型二</Option>
                <Option value="房型三">房型三</Option>
                <Option value="房型四">房型四</Option>
            </Select>
        </FormItem>
        <FormItem>
            <Select placeholder="房号" style="width: 100px;">
                <Option value="">全部</Option>
                <Option value="201">201</Option>
                <Option value="202">202</Option>
                <Option value="203">203</Option>
                <Option value="204">204</Option>
            </Select>
        </FormItem>
        <FormItem>
            <Input placeholder="姓名/电话"></Input>
        </FormItem>
        <FormItem>
            <Button type="primary">查询</Button>
        </FormItem>
    </Form>
    <div class="cls"></div>
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
                        width: 120
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
                        title: '入住/退房时间',
                        width: 180
                    },
                    {
                        title: '入住房间'
                    },
                    {
                        title: '应收金额',
                        width: 100
                    },
                    {
                        title: '待收金额',
                        width: 100
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
                                    },
                                    on:{
                                        click: ()=>{
                                            this.turnUrl('/admin/checkstandView/0');
                                        }
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