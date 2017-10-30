<template>
<div>
    <Form inline class="fr">
        <FormItem>
            <Select placeholder="阅读状态" style="width: 100px;">
                <Option value="">全部</Option>
                <Option value="1">未读</Option>
                <Option value="2">已读</Option>
            </Select>
        </FormItem>
        <FormItem>
            <DatePicker type="date" placeholder="发送日期"></DatePicker>
        </FormItem>
        <FormItem>
            <Button type="primary">查询</Button>
        </FormItem>
    </Form>
    <div class="cls"></div>
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
                        title: '主题',
                        key: 'title'

                    },
                    {
                        title: '发送时间',
                        width: 180,
                        key: 'publicDate'
                    },
                    {
                        title: '阅读状态',
                        width: 180,
                        key: 'hasRead'
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
                                    on: {
                                        click: ()=>{
                                            this.turnUrl('/admin/personNoticeInfo/'+params.row.id)
                                        }
                                    }
                                }, '查看')
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
            pageTo(page){
                this.current=page;
                this.refresh();
            },
            refresh (){
                var that=this;
                this.host.post('mchNoticeList',{page: this.current}).then(function(res){
                    if(res.isSuccess()){
                        that.data=res.data().list;
                        that.totalCount=res.data().totalCount;
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