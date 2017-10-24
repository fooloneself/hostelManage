<template>
<div>
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
                                            this.turnUrl('/personNoticeInfo/'+params.row.id)
                                        }
                                    }
                                }, '查看')
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
            this.host.post('mchNoticeList').then(function(res){
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
        },
        methods:{
            turnUrl:function(url,query){
                this.$router.push(url)
            }
        }
    }
</script>