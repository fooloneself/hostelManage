<template>
<div>
    <Form v-model="filter" inline class="fr">
        <FormItem>
            <Select placeholder="异常原因" style="width: 100px;">
                <Option value="">全部</Option>
                <Option v-for="(ab,a) in abnormal" :value="ab.key">{{ab.value}}</Option>
            </Select>
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
            <Button @click="query" type="primary">查询</Button>
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
                        title: '异常原因',
                        width: 100,
                        key: 'abnormal'
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
                data: [],
                totalCount: 0,
                roomTypes: [],
                rooms:[],
                channels:[],
                abnormal:[],
                filter:{
                    roomId: 0,
                    typeId: 0,
                    search: '',
                    page: 1,
                    pageSize: 10,
                    isNormal: 0,
                    checkIn:-1,
                    checkOut:-1,
                    abnormal:''
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
            this.host.post('merchantAllOrderAbnormal').then(function(res){
                if(res.isSuccess()){
                    that.abnormal=res.data();
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