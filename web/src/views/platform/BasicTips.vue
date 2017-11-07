<style scoped>
.commit{
    padding: 16px;
    border: 1px solid #ccf5e0;
    background: #e6faf0;
    border-radius: 6px;
    color: #657180;
    margin-top: 8px;
    line-height: 22px;
    a{
        color: #16A085;
        margin-left: 16px;
        float: right;
    }
}
</style>

<template>
<div>
    <Form inline class="fr">
        <FormItem>
            <Select placeholder="回复状态" v-model="filter.status" style="width: 100px;">
                <Option value="-1">全部</Option>
                <Option value="0">未回复</Option>
                <Option value="1">已回复</Option>
            </Select>
        </FormItem>
        <FormItem>
            <Button type="primary" @click="search">查询</Button>
        </FormItem>
    </Form>
    <div class="cls"></div>
    <Collapse v-model="value" accordion>
    <template>
        <Panel v-for="item in list">
            {{item.name}} - {{item.date}}
            <div slot="content">{{item.content}}
            <div class="commit" v-show="item.hasAnswer">
                {{item.answer}}
                <a href="javascript:;" v-show="item.canCancel" @click="cancelAnswer(item)">
                    <i class="fa fa-trash-o icon-mr" aria-hidden="true"></i>删除
                </a>
                <div class="cls"></div>
            </div>
            <div v-show="!item.hasAnswer" class="mt">
                <Input v-model="item.answer" type="textarea" :rows="3" placeholder="请输入回复内容..."></Input>
                <Button type="primary" @click="answer(item)" class="mt">回复</Button>
            </div>
            </div>
        </Panel>
    </template>
    </Collapse>
    <div class="mb"></div>
    <Page :total="totalCount" show-total :current="filter.page" :page-size="filter.pageSize" @on-change="pageTo"></Page>
</div>
</template>
<script>
    export default {
        data () {
            return {
                value: '0',
                totalCount: 0,
                list :[],
                filter: {
                    status: -1,
                    page: 2,
                    pageSize: 10
                }
            }
        },
        mounted (){
            this.refresh();
        },
        methods:{
            turnUrl:function(url,query){
                this.$router.push(url)
            },
            search(){
                this.filter.page=1;
                this.refresh();
            },
            pageTo (page){
                this.filter.page=page;
                this.refresh();
            },
            refresh (){
                var that=this;
                this.host.post('platformFeedbackList',this.filter).then(function(res){
                    if(res.isSuccess()){
                        that.totalCount=parseInt(res.data().totalCount);
                        that.list=res.data().list;
                    }else{
                        this.$Notice.info({
                            title: '错误提示',
                            desc: res.error()
                        })
                    }
                })
            },
            answer (item){
                this.host.post('platformFeedbackAnswer',{id: item.id,answer: item.answer}).then(function(res){
                    if(res.isSuccess()){
                        item.hasAnswer=true;
                        item.canCancel=true;
                    }else{
                        this.$Notice.info({
                            title: '错误提示',
                            desc: res.error()
                        })
                    }
                })
            },
            cancelAnswer (item){
                this.host.post('platformFeedbackCancel',{id: item.id}).then(function(res){
                    if(res.isSuccess()){
                        item.hasAnswer=false;
                        item.answer='';
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