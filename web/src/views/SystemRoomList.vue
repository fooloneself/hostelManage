<template>
<div>
    <Button type="primary" @click="turnUrl('/roomListEdit/0')">新增</Button>
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
                        key: 'typeName'
                    },
                    {
                        title: '默认价格',
                        width: 100,
                        key: 'defaultPrice'
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
                                            this.turnUrl('roomListEdit')
                                        }
                                    }
                                }, '编辑'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '删除'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '锁房')
                            ]);
                        }
                    }
                ],
                data: [],
                totalCount: 0
            }
        },
        mounted (){
            this.host.post('roomList').then(function(res){
                if(res.isSuccess()){
                    this.data=res.data().list;
                    this.totalCount=parseInt(res.data().totalCount);
                }else{
                    alert(res.error());
                }
            })
        },
        methods:{
            turnUrl:function(url){
                this.$router.push(url)
            }
        }
    }
</script>