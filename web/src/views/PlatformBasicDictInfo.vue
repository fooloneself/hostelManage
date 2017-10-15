<template>
<div>
    <Button type="primary" @click="goBack"><i class="fa fa-chevron-left icon-mr" aria-hidden="true"></i>返回</Button>
    <Button type="primary" @click="toAdd">新增</Button>
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
                                            this.turnUrl('/basicDictInfoEdit/'+params.row.code+'/'+params.row.id);
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
                totalCount:0
            }
        },
        mounted (){
            var that=this;
            this.host.post('dictionaryItemList',{'code':this.$route.params.code}).then(function(res){
                if(res.isSuccess()){
                    that.data=res.data().list;
                    that.totalCount=parseInt(res.data().totalCount);
                }else{
                    alert(res.error());
                }
            })
        },
        methods:{
            turnUrl:function(url){
                this.$router.push(url)
            },
            goBack:function(){
                history.go(-1);
            }
            toAdd:function(){
                this.turnUrl('/basicDictInfoEdit/'+this.$route.params.code+'/0');
            },
            deleteItem:function(id){
                this.host.post('dictionaryItemDelete',{id:id}).then(function(res){
                    if(res.isSuccess()){
                        location.reload();
                    }else{
                        alert(res.error());
                    }
                })
            }
        }
    }
</script>