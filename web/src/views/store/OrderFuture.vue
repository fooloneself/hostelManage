<template>
<div>
    <Form v-model="filter" inline class="fr">
        <FormItem>
            <DatePicker v-model="filter.checkIn" type="date" placeholder="预订日期"></DatePicker>
        </FormItem>
        <FormItem>
            <Select v-model="filter.channel" placeholder="客人来源" style="width: 100px;">
                <Option value="0">全部</Option>
                <Option v-for="(channel,c) in channels" :value="channel.id">{{channel.name}}</Option>
            </Select>
        </FormItem>
        <FormItem>
            <Select v-model="filter.typeId" placeholder="房屋类型" style="width: 100px;">
                <Option value="0">全部</Option>
                <Option v-for="(roomType,rt) in roomTypes" :value="roomType.id">{{roomType.name}}</Option>
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
                        width: 130,
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
                                            this.turnUrl('/admin/checkstandOrderView')
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
                                            this.turnUrl('/admin/checkstandOrderEdit')
                                        }
                                    }
                                }, '修改订单'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '删除订单')
                            ]);
                        }
                    }
                ],
                data: [],
                totalCount: 0,
                roomTypes: [],
                rooms:[],
                channels:[],
                filter:{
                    roomId: 0,
                    typeId: 0,
                    search: '',
                    page: 1,
                    pageSize: 10,
                    isNormal: 1,
                    checkIn:0,
                    checkOut:-1,
                    channel:0,
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
            this.host.post('channelAll').then(function(res){
                if(res.isSuccess()){
                    that.channels=res.data();
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
                var checkIn=this.filter.checkIn?Math.floor(Date.parse(new Date(this.filter.checkIn))/1000):-1;
                var param={
                    roomId: this.filter.roomId,
                    typeId: this.filter.typeId,
                    search: this.filter.search,
                    page: this.filter.page,
                    pageSize: this.filter.pageSize,
                    isNormal: this.filter.isNormal,
                    checkIn:checkIn,
                    checkOut:-1,
                    channel:this.filter.channel,
                };
                this.host.post('merchantOrderList',param).then(function(res){
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