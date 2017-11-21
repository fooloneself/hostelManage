<template>
<div>
    <Row>
        <Col span="6">
            <Button @click="turnUrl('/admin/memberListEdit/0')" type="primary">新增</Button>
        </Col>
        <Col span="18" class="tr">
            <Form v-model="filter" inline>
                <FormItem>
                    <Select v-model="filter.rank" placeholder="会员等级" class="tl" style="width: 100px;">
                        <Option value="0">全部</Option>
                        <Option v-for="rank in ranks" :value="rank.id">{{rank.name}}</Option>
                    </Select>
                </FormItem>
                <FormItem>
                    <Input v-model="filter.search" placeholder="姓名/电话"></Input>
                </FormItem>
                <FormItem>
                    <Button @click="query" type="primary">查询</Button>
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
                        width: 120,
                        key: 'balance'
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
                        width: 200,
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
                                }, '移入黑名单'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            var that=this;
                                            this.$Modal.confirm({
                                                title:'提示',
                                                content:'确定要删除此会员？',
                                                onOk(){
                                                    that.delete(params.row.id)
                                                }
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
                ranks:[],
                filter:{
                    rank:0,
                    search:''
                }
            }
        },
        mounted (){
            var that=this;
            this.query();
            this.host.post('merchantMemberAllRank').then(function(res){
                if(res.isSuccess()){
                    that.ranks=res.data();
                }else{
                    that.$Notice.info({
                        title: '提示',
                        desc: res.error()
                    })
                }
            })
        },
        methods:{
            turnUrl:function(url){
                this.$router.push(url)
            },
            delete (id){
                var that=this;
                this.host.post('merchantMemberDelete',{id: id}).then(function(res){
                    if(res.isSuccess()){
                        that.query();
                    }else{
                        this.$Notice.info({
                            title: '提示',
                            desc: res.error()
                        })
                    }
                })
            },
            query (){
                var that=this;
                this.host.post('merchantMemberList',this.filter).then(function(res){
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
            }
        }
    }
</script>