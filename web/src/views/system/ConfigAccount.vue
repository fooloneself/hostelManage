<template>
<div>
    <Button type="primary" @click="turnUrl('/admin/configAccountEdit')">新增</Button>
    <div class="mb"></div>
    <Table :columns="columns" :data="data" stripe></Table>
    <div class="mb"></div>
    <Page :total="totalCount" :page-size="pageSize" :current="page" @on-change="pageTo" show-total></Page>
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
                        title: '登录账号',
                        key: 'userName'
                    },
                    {
                        title: '姓名',
                        width: 200,
                        key: 'name'
                    },
                    {
                        title: '分配角色',
                        width: 200,
                        key: 'roleName'
                    },
                    {
                        title: '数据状态',
                        width: 200,
                        key: 'statusLabel'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        fixed: 'right',
                        width: 240,
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
                                            this.turnUrl('/admin/personPassword/'+params.row.id);
                                        }
                                    }
                                }, '重置密码'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/powerAccountRoleEdit/'+params.row.id)
                                        }
                                    }
                                }, '分配角色')
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
        mounted (){
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
            refresh (){
                var that=this;
                this.host.post('merchantAdminList',{page: this.page,pageSize: this.pageSize}).then(function(res){
                    if(res.isSuccess()){
                        that.data=res.data().list;
                        that.totalCount=res.data().totalCount;
                    }else{
                        this.$Notice.info({
                            title: '错误提示',
                            desc: res.error()
                        })
                    }
                })
            },
            delete (id){
                var that=this;
                this.host.post('merchantAdminDel',{adminId: id}).then(function(res){
                    if(res.isSuccess()){
                        that.refresh();
                    }else{
                        this.$Notice.info({
                            title: '提示信息',
                            desc: res.error()
                        })
                    }
                })
            },
            change (item){
                var that=this;
                this.host.post('merchantAdminChange',{adminId: item.id}).then(function(res){
                    if(res.isSuccess()){
                        item.status=item.status==1?0:1;
                        item.statusLabel=item.status==1?'启用':'停用';
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