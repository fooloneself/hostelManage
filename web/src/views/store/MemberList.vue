<template>
<div>
    <Row>
        <Col span="6">
            <Button @click="turnUrl('/admin/memberListEdit/0')" type="primary">新增</Button>
        </Col>
        <Col span="18" class="tr">
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
        </Col>
    </Row>
    <Row>
        <Col span="24">
            <Table :columns="columns" :data="data" stripe></Table>
            <div class="mb"></div>
            <Page :total="totalCount" show-total></Page>
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
                                            this.turnUrl('/admin/memberListEdit/'+params.row.id)
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
                                            var res=confirm('确定要删除吗？');
                                            if(res){
                                                this.delete(params.row.id);
                                            }
                                        }
                                    }
                                }, '删除'),
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
                data: [],
                totalCount: 0
            }
        },
        mounted (){
            var that=this;
            this.host.post('merchantMemberList').then(function(res){
                if(res.isSuccess()){
                    that.data=res.data().list;
                    that.totalCount=res.data().totalCount;
                }else{
                    that.$Notice.info({
                        title:'提示',
                        desc:res.error()
                    })
                }
            })
        },
        methods:{
            turnUrl:function(url){
                this.$router.push(url)
            },
            delete (id){
                this.host.post('merchantMemberDelete',{id: id}).then(function(res){
                    if(res.isSuccess()){
                        this.$router.go(0);
                    }else{
                        this.$Notice.info({
                            title: '提示',
                            desc: res.error()
                        })
                    }
                })
            }
        }
    }
</script>