<template>
<div>
    <Button type="ghost" @click="goUp"><i class="fa fa-chevron-left icon-mr" aria-hidden="true"></i>返回列表</Button>
    <Button type="primary" @click="toAdd" class="icon-ml">新增</Button>
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
                        title: '字典名称',
                        key: 'label'
                    },
                    {
                        title: '数据项',
                        width: 180,
                        key: 'key'
                    },
                    {
                        title: '数据值',
                        width: 180,
                        key: 'value'
                    },
                    {
                        title: '排序',
                        width: 100,
                        key: 'order'
                    },
                    {
                        title: '操作',
                        key: 'action',
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
                                            this.turnUrl('/admin/basicDictInfoEdit/'+params.row.code+'/'+params.row.id);
                                        }
                                    }
                                }, '编辑'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on:{
                                        click:()=>{
                                            var res=confirm('确定要删除吗？');
                                            if(res)this.deleteItem(params.row.id);
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                data: [],
                totalCount:0,
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
            goUp:function(){
                this.$router.push('/admin/basicDict');
            },
            toAdd:function(){
                this.turnUrl('/admin/basicDictInfoEdit/'+this.$route.params.code+'/0');
            },
            deleteItem:function(id){
                this.host.post('dictionaryItemDelete',{id:id}).then(function(res){
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
            refresh(){
                var that=this;
                this.host.post('dictionaryItemList',{'code':this.$route.params.code, page: this.current}).then(function(res){
                    if(res.isSuccess()){
                        that.data=res.data().list;
                        that.totalCount=parseInt(res.data().totalCount);
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