<template>
<Row>
	<Col span="6">
		<h3 class="mb">活动执行计划</h3>
		<Form v-model="planForm" label-position="right" :label-width="80">
            <FormItem label="活动类型：">{{activity.type}}</FormItem>
			<FormItem label="活动名称：">{{activity.name}}</FormItem>
			<FormItem label="时间周期：">
				<DatePicker v-model="planForm.during" type="daterange" placeholder="选择日期"></DatePicker>
	        </FormItem>
	        <FormItem>
				<Button @click="planActivity" type="primary">添加</Button>
				<Button type="ghost" @click="goBack" class="icon-ml">返回</Button>
	        </FormItem>
		</Form>		
	</Col>
	<Col span="17" offset="1">
		<Table :columns="columns" :data="data" stripe></Table>
		<div class="mb"></div>
    	<Page :total="totalCount" :current-page="filter.page" :page-size="filter.pageSize" @on-change="pageTo" show-total></Page>
	</Col>
</Row>
</template>
<script>
export default{
	data () {
		return {
            columns: [
                {
                    title: '序号',
                    width: 60,
                    type:'index'
                },
                {
                    title: '活动执行计划',
                    key:'activeDate'
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
                                            var that=this;
                                            this.$Modal.confirm({
                                                title:'提示',
                                                content:'请确认是否要删除？',
                                                onOk(){
                                                    that.delPlan(params.row.id);
                                                }
                                            });
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                }
            ],
            data: [],
            totalCount:0,
            filter:{
                page:1,
                pageSize:10
            },
            activity:{},
            planForm:{}
		}
	},
	mounted(){
	    var that=this;
        if(this.$route.params.activeId>0){
            this.host.post('merchantActivityInfo',{id:this.$route.params.activeId}).then(function(res){
                if(res.isSuccess()){
                    that.activity=res.data();
                }else{
                    this.$Notice.info({
                        title:'错误提示',
                        desc:res.error()
                    })
                }
            })
        }
        this.refresh();
	},
	methods:{
        goBack(){
            this.$router.go(-1);
        },
        pageTo(page){
            this.filter.page=page;
            this.refresh();
        },
        refresh(){
            var that=this;
            this.filter.activeId=this.$route.params.activeId
            this.host.post('merchantActivityPlans',this.filter).then(function(res){
                if(res.isSuccess()){
                    that.data=res.data().list;
                    that.totalCount=res.data().totalCount;
                }else{
                    this.$Notice.info({
                        title:'错误提示',
                        desc:res.error()
                    })
                }
            })
        },
        getTimestamp(date){
            if(date){
                return Math.floor(Date.parse(new Date(date))/1000);
            }else{
                return 0;
            }
        },
        planActivity(){
            var that=this;
            this.host.post('merchantActivityAddPlan',{
                id:this.$route.params.activeId,
                start:this.getTimestamp(this.planForm.during[0]),
                end:this.getTimestamp(this.planForm.during[1]),
            }).then(function(res){
                if(res.isSuccess()){
                    that.pageTo(1);
                }else{
                    this.$Notice.info({
                        title:'错误提示',
                        desc:res.error()
                    })
                }
            })
        },
        delPlan(id){
            var that=this;
            this.host.post('merchantActivityDelPlan',{planId:id,activeId:this.$route.params.activeId}).then(function(res){
                if(res.isSuccess()){
                    that.refresh();
                }else{
                    this.$Notice.info({
                        title:'错误提示',
                        desc:res.error()
                    })
                }
            })
        }
	}
}
</script>