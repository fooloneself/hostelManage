<template>
<div>
    <Button type="primary" @click="turnUrl('/admin/configChannelEdit/0')">新增</Button>
    <div class="mb"></div>
    <Table :columns="columns" :data="data" stripe></Table>
    <div class="mb"></div>
    <Page :total="dataCount" @on-change="pageTo" :page-size="10" show-total></Page>
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
                        title: '渠道名称',
                        width: 200,
                        key: 'name'
                    },
                    {
                        title: '设置佣金',
                        width: 150,
                        key: 'commission'
                    },
                    {
                        title: '渠道说明',
                        key: 'introduce'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 150,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/configChannelEdit/'+params.row.id)
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
                                }, '删除')
                            ]);
                        }
                    }
                ],
                data: [],
                dataCount: 0,
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
            delete(id){
                this.host.post('channelDel',{id: id}).then(function(res){
                    if(res.isSuccess()){
                        this.$router.go(0);
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
                this.host.post('channelList',{page: this.current}).then(function(res){
                    if(res.isSuccess()){
                        that.dataCount=res.data().totalCount;
                        that.data=res.data().list;
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