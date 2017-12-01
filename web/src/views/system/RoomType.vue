<template>
<div>
    <Button type="primary" @click="turnUrl('/admin/roomTypeEdit/0')">新增</Button>
    <div class="mb"></div>
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
                        key: 'name'
                    },
                    {
                        title: '默认价格',
                        width: 100,
                        key: 'default_price'
                    },
                    {
                        title: '今日价格',
                        width: 100
                    },
                    {
                        title: '房型说明',
                        key: 'introduce'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 180,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small',
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/roomTypeEdit/'+params.row.id)
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
                                            var that=this;
                                            this.$Modal.confirm({
                                                title: '提示',
                                                content: '确定要删除吗',
                                                onOk (){
                                                    that.deleteType(params.row.id);
                                                }
                                            })
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
                                            this.turnUrl('/admin/roomTypeFloat/'+params.row.id);
                                        }
                                    }
                                }, '浮动价格')
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
            turnUrl:function(url,query){
                this.$router.push(url)
            },
            deleteType:function(id){
                this.host.post('roomTypeDelete',{id:id}).then(function(res){
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
                this.host.post('roomTypes',{page: this.current}).then(function(res){
                    if(res.isSuccess()){
                        that.data=res.data().list;
                        that.totalCount=parseInt(res.data().total);
                    }else{
                        that.$Notice.info({
                            title: '提示',
                            desc: res.error()
                        });
                    }
                })
            }
        }
    }
</script>