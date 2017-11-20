<template>
<div>
    <Form v-model="filter" inline class="fr">
        <FormItem>
            <Select v-model="filter.typeId" placeholder="房屋类型" style="width: 100px;">
                <Option value="0">全部</Option>
                <Option v-for="(roomType,index) in roomTypes" :value="roomType.id">{{roomType.name}}</Option>
            </Select>
        </FormItem>
        <FormItem>
            <Select v-model="filter.roomId" placeholder="房号" style="width: 100px;">
                <Option value="0">全部</Option>
                <Option v-for="(room,i) in rooms" :value="room.id">{{room.number}}</Option>
            </Select>
        </FormItem>
        <FormItem>
            <Input v-model="filter.search" placeholder="姓名/电话"></Input>
        </FormItem>
        <FormItem>
            <Button @click="query" type="primary">查询</Button>
        </FormItem>
    </Form>
    <div class="cls"></div>
    <Table :columns="columns" :data="data" stripe></Table>
    <div class="mb"></div>
    <Page :total="totalCount" :current="filter.page" :page-size="filter.pageSize" @on-change="query" show-total></Page>
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
                        title: '客人来源',
                        width: 120,
                        key: 'channelName'
                    },
                    {
                        title: '入住人',
                        width: 100,
                        key: 'personName'
                    },
                    {
                        title: '手机号',
                        width: 100,
                        key: 'mobile'
                    },
                    {
                        title: '入住/退房时间',
                        width: 180,
                        key: 'date'
                    },
                    {
                        title: '入住房间',
                        key: 'number'
                    },
                    {
                        title: '应收金额',
                        width: 100,
                        key: 'amountPayable'
                    },
                    {
                        title: '待收金额',
                        width: 100,
                        key: 'amountDeffer'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        fixed: 'right',
                        width: 160,
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
                                }, '查看'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '续住'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on:{
                                        click: ()=>{
                                            this.turnUrl('/admin/checkstandOut');
                                        }
                                    }
                                }, '退房')
                            ]);
                        }
                    }
                ],
                data: [],
                totalCount: 0,
                roomTypes: [],
                rooms:[],
                filter:{
                    roomId: 0,
                    typeId: 0,
                    search: '',
                    page: 1,
                    pageSize: 10,
                    isNormal: 1,
                    checkIn:-1,
                    checkOut:0
                }
            }
        },
        mounted (){
            var that=this;
            this.host.post('merchantAllRoomType').then(function(res){
                if(res.isSuccess()){
                    that.roomTypes=res.data();
                }else{
                    this.$Notice.info({
                        title: '错误提示',
                        desc: res.error()
                    })
                }
            })
            this.host.post('merchantAllRoom').then(function(res){
                if(res.isSuccess()){
                    that.rooms=res.data();
                }else{
                    this.$Notice.info({
                        title: '错误提示',
                        desc: res.error()
                    })
                }
            })
            this.query();
        },
        methods:{
            turnUrl:function(url,query){
                this.$router.push(url)
            },
            query(){
                var that=this;
                this.host.post('merchantOrderList',this.filter).then(function(res){
                    if(res.isSuccess()){
                        that.totalCount=res.data().totalCount;
                        that.data=res.data().list;
                    }else{
                        this.$Notice.info({
                            title: '错误提示',
                            desc: res.error()
                        })
                    }
                })
            }
        }
    }
</script>