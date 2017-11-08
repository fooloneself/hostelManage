<template>
<div>
    <Button @click="turnUrl('/admin/powerRoleEdit/0')" type="primary">新增</Button>
    <div class="mb"></div>
    <Table :columns="columns" :data="data" stripe></Table>
    <div class="mb"></div>
    <Page :total="totalCount" :current="page" :page-size="pageSize" @on-change="pageTo" show-total></Page>
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
                        title: '角色名称',
                        width: 180,
                        key: 'name'
                    },
                    {
                        title: '商户可用',
                        width: 100,
                        key: 'mchCan'
                    },
                    {
                        title: '数据状态',
                        width: 100,
                        key: 'statusLabel'
                    },
                    {
                        title: '角色说明',
                        key: 'mark'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        fixed: 'right',
                        width: 260,
                        render: (h, params) => {
                            var operate=params.row.status==1?'停用':'启用';
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/powerRoleEdit/'+params.row.id)
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
                                                    that.delete(params.row.id);
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
                                            var that=this;
                                            this.$Modal.confirm({
                                                title: '提示',
                                                content: '确定要'+operate+'吗',
                                                onOk (){
                                                    that.change(params.row);
                                                }
                                            })
                                        }
                                    }
                                }, operate),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/powerRoleEdit')
                                        }
                                    }
                                }, '克隆'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/powerRoleAccountEdit')
                                        }
                                    }
                                }, '分配账号')
                            ]);
                        }
                    }
                ],
                data: [],
                totalCount: 0,
                page: 1,
                pageSize: 10
            }
        },
        mounted(){
            this.refresh();
        },
        methods:{
            turnUrl:function(url,query){
                this.$router.push(url)
            },
            pageTo (page){
                this.page=page;
                this.refresh();
            },
            refresh(){
                var that=this;
                this.host.post('platformRoleList',{page: this.page,pageSize: this.pageSize}).then(function(res){
                    if(res.isSuccess()){
                        that.data=res.data().list;
                        that.totalCount=res.data().totalCount;
                    }else{
                        this.$Notice.info({
                            title:'错误提示',
                            desc: res.error()
                        })
                    }
                })
            },
            delete(id){
                var that=this;
                this.host.post('platformRoleDel',{roleId: id}).then(function(res){
                    if(res.isSuccess()){
                        that.refresh();
                    }else{
                        this.$Notice.info({
                            title: '错误提示',
                            desc: res.error()
                        })
                    }
                })
            },
            change (item){
                this.host.post('platformRoleChangeStatus',{roleId: item.id}).then(function(res){
                    if(res.isSuccess()){
                        if(item.status==1){
                            item.status=0;
                            item.statusLabel='停用';
                        }else{
                            item.status=1;
                            item.statusLabel='启用';
                        }
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