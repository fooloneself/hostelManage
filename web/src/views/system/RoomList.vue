<template>
<div>
    <Row>
        <Col span="6">
            <Button type="primary" @click="turnUrl('/admin/roomListEdit/0')" class="icon-mr">单个新增</Button>
            <Button type="primary" @click="turnUrl('/admin/roomListMulti/0')">批量新增</Button>
        </Col>
        <Col span="18">
            <Form inline class="fr">
                <FormItem>
                    <Select placeholder="房间类型" style="width: 100px;">
                        <Option value="">全部</Option>
                        <Option value="1">房型一</Option>
                        <Option value="2">房型二</Option>
                    </Select>
                </FormItem>
                <FormItem>
                    <Select placeholder="锁房状态" style="width: 100px;">
                        <Option value="">全部</Option>
                        <Option value="正常">正常</Option>
                        <Option value="锁房">锁房</Option>
                    </Select>
                </FormItem>
                <FormItem>
                    <Button type="primary">查询</Button>
                </FormItem>
            </Form>
        </Col>
    </Row>
    <Table :columns="columns" :data="data" stripe></Table>
    <div class="mb"></div>
    <Page :total="totalCount" @on-change="pageTo" :page-size="10" show-total></Page>
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
                        title: '房间类型',
                        width: 180,
                        key: 'typeName'
                    },
                    {
                        title: '房号',
                        width: 100,
                        key: 'number'
                    },
                    {
                        title: '锁房状态',
                        width: 100,
                        key: 'isLock'
                    },
                    {
                        title: '房间配套',
                        key: 'serverName'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 160,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/roomListEdit/'+params.row.id)
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
                                    },
                                    on: {
                                        click: ()=>{
                                            var res=confirm('确定要锁房？');
                                            if(res){
                                                this.host.post('roomLock',{id: params.row.id}).then(function(res){
                                                    if(res.isSuccess()){
                                                        params.row.isLock='是';
                                                    }else{
                                                        alert(res.error());
                                                    }
                                                })
                                            }
                                        }
                                    }
                                }, '锁房')
                            ]);
                        }
                    }
                ],
                data: [],
                totalCount: 0,
                current: 1
            }
        },
        mounted (){
            this.refresh();
        },
        methods:{
            turnUrl:function(url){
                this.$router.push(url)
            },
            delete:function(id){
                this.host.post("roomDelete",{id: this.$route.params.id}).then(function(res){
                    if(res.isSuccess()){
                        location.reload();
                    }else{
                        this.$Notice.info({
                            title: '提示',
                            desc: res.error()
                        });
                    }
                })
            },
            pageTo (page){
                this.current=page;
                this.refresh();
            },
            refresh (){
                var that=this;
                this.host.post('roomList',{page: this.current}).then(function(res){
                    if(res.isSuccess()){
                        that.data=res.data().list;
                        that.totalCount=parseInt(res.data().totalCount);
                    }else{
                        this.$Notice.info({
                            title: '提示',
                            desc: res.error()
                        });
                    }
                })
            }
        }
    }
</script>