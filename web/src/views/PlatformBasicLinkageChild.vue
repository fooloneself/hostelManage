<template>
<div>
    <Button type="primary" @click="goUp"><i class="fa fa-chevron-left icon-mr" aria-hidden="true"></i>上一级</Button>
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
                        title: '上级菜单',
                        width: 180,
                        key: 'parent_label'
                    },
                    {
                        title: '菜单名称',
                        width: 180,
                        key: 'label'
                    },
                    {
                        title: '菜单顺序',
                        width: 100,
                        key: 'order'
                    },
                    {
                        title: '菜单描述',
                        key: 'introduce'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 280,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/basicLinkageChild/'+this.$route.params.code+'/'+params.row.id)
                                            this.$router.go(0);
                                        }
                                    }
                                }, '管理子菜单'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/basicLinkageChildEdit/'+this.$route.params.code+'/'+params.row.id+'/0')
                                        }
                                    }
                                }, '新增子菜单'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/basicLinkageChildEdit/'+this.$route.params.code+'/'+params.row.pid+'/'+params.row.id)
                                        }
                                    }
                                }, '编辑'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                data: [],
                totalCount: 0
            }
        },
        mounted(){
            var that=this;
            this.host.post('linkageMenuItemList',{code: this.$route.params.code,pid:this.$route.params.pid}).then(function(res){
                if(res.isSuccess()){
                    that.totalCount=res.data().totalCount;
                    that.data=res.data().list;
                }else{
                    that.$Notice.info({
                        title:'提示',
                        desc: res.error()
                    })
                }
            })
        },
        methods:{
            turnUrl:function(url){
                this.$router.push(url);
            },
            toAdd (){
                this.$router.push('/basicLinkageChildEdit/'+this.$route.params.code+'/'+this.$route.params.pid+'/0');
            },
            goUp:function(){
            }
        }
    }
</script>