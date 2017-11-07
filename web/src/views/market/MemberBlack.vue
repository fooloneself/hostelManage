<template>
<div>
    <div class="tr">
        <Form inline>
            <FormItem>
                <Select placeholder="会员等级" class="tl" style="width: 100px;">
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
    </div>
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
                        key: 'name'
                    },
                    {
                        title: '手机号',
                        width: 120,
                        key: 'mobile'
                    },
                    {
                        title: '会员等级',
                        width: 120,
                        key: 'rank'
                    },
                    {
                        title: '余额',
                        width: 120
                    },
                    {
                        title: '消费金额',
                        width: 120,
                        key: 'consumption_amount'
                    },
                    {
                        title: '积分',
                        width: 80,
                        key: 'integral'
                    },
                    {
                        title: '注册时间',
                        width: 120,
                        key: 'register_date'
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
                                            this.turnUrl('/admin/memberRankEdit')
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
                                                content:'会员等级删除以后，该等级下的会员会自动匹配至他满足条件的等级，请确认是否要删除？'
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