<template>
<div>
    <Button type="primary" @click="turnUrl('/roomTypeEdit/0')">新增</Button>
    <div class="mb"></div>
    <Table :columns="columns" :data="data" stripe></Table>
    <div class="mb"></div>
    <Page :total="totalCount" show-total></Page>
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
                                            this.turnUrl('roomTypeEdit/'+params.row.id)
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
                                            var res=confirm('确定要删除吗');
                                            if(res){
                                                this.deleteType(params.row.id);
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
                                            this.turnUrl('/roomTypeFloat/'+params.row.id);
                                        }
                                    }
                                }, '浮动价格')
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
            this.host.post('roomTypes').then(function(res){
                if(res.isSuccess()){
                    that.data=res.data().list;
                    that.totalCount=parseInt(res.data().total);
                }else{
                    alert(res.error());
                }
            })
        },
        methods:{
            turnUrl:function(url,query){
                this.$router.push(url)
            },
            deleteType:function(id){
                this.host.post('roomTypeDelete',{id:id}).then(function(res){
                    if(res.isSuccess()){
                        alert('删除成功');
                        location.reload();
                    }else{
                        alert(res.error());
                    }
                })
            }
        }
    }
</script>