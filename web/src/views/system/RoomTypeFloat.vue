<style scoped>
.title{
    height: 53px;
    line-height: 53px;
    font-weight: bolder;
}
</style>

<template>
<div style="margin-top: -24px;">
	<Tabs value="week">
        <span class="title" slot="extra">房屋类型：豪华大床房</span>
        <TabPane label="周价格浮动" name="week">
	        <Row>
	        	<Col span="10">
			        <Form v-model="weekPrice" label-position="right" :label-width="80">
						<FormItem label="周一价格：">
							<Input v-model="weekPrice.monday"></Input>
				        </FormItem>
						<FormItem label="周二价格：">
							<Input v-model="weekPrice.tuesday"></Input>
				        </FormItem>
						<FormItem label="周三价格：">
							<Input v-model="weekPrice.wensday"></Input>
				        </FormItem>
						<FormItem label="周四价格：">
							<Input v-model="weekPrice.thursday"></Input>
				        </FormItem>
						<FormItem label="周五价格：">
							<Input v-model="weekPrice.friday"></Input>
				        </FormItem>
						<FormItem label="周六价格：">
							<Input v-model="weekPrice.saturday"></Input>
				        </FormItem>
						<FormItem label="周日价格：">
							<Input v-model="weekPrice.sunday"></Input>
				        </FormItem>
						<FormItem>
					        <Button @click="submitWeekPrice" type="primary">保存</Button>
                            <Button type="ghost" class="icon-ml">返回</Button>
					    </FormItem>
				    </Form>
	        	</Col>
	        </Row>
        </TabPane>
        <TabPane label="日价格浮动" name="day">
        <Row>
        	<Col span="6">
        		<Form v-model="dayPrice" label-position="right" :label-width="80">
					<FormItem label="日期范围：">
						<DatePicker v-model="dayPrice.during" type="daterange"></DatePicker>
					</FormItem>
					<FormItem label="浮动价格：">
						<Input v-model="dayPrice.price"></Input>
			        </FormItem>
					<FormItem label="浮动说明：">
						<Input v-model="dayPrice.mark" type="textarea" :rows="14"></Input>
			        </FormItem>
					<FormItem>
				        <Button @click="addDayPrice" type="primary">添加</Button>
                        <Button type="ghost" class="icon-ml">返回</Button>
				    </FormItem>
			    </Form>
        	</Col>
        	<Col span="17" offset="1">
        		<Form inline class="tr">
					<FormItem>
						<DatePicker v-model="filter.searchDate" type="date" placeholder="请选择查询日期"></DatePicker>
					</FormItem>
			    </Form>
				<Table :columns="columns" :data="dayPrices" stripe></Table>
			    <div class="mb"></div>
			    <Page :total="totalCount" :current-page="filter.page" :page-size="filter.pageSize" @on-change="pageTo" show-total></Page>
        	</Col>
        </Row>
        </TabPane>
    </Tabs>
</div>
</template>

<script>
	export default {
	    data (){
	        return {
	            weekPrice: {
                    monday: null,
                    tuesday: null,
                    wensday: null,
                    thursday: null,
                    friday: null,
                    saturday: null,
                    sunday: null
                },
                columns: [
                    {
                        title: '序号',
                        width: 60,
                        type:'index'
                    },
                    {
                        title: '日期范围',
                        width: 140,
                        key:'during'
                    },
                    {
                        title: '价格',
                        width: 60,
                        key:'price'
                    },
                    {
                        title: '说明',
                        key:'mark'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 120,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on:{
                                        click: ()=>{
                                            this.refreshDayPrice(params.row.id);
                                        }
                                    }
                                }, '编辑'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on:{
                                        click: ()=>{
                                            var that=this;
                                            this.$Modal.confirm({
                                                title: '提示',
                                                content: '确定要删除吗',
                                                onOk (){
                                                    that.deleteDayPrice(params.row.id);
                                                }
                                            })
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                dayPrices: [],
                totalCount:0,
                filter:{
                    page:1,
                    pageSize:10,
                    searchDate:'',
                },
                dayPrice:{
                    id:0,
                    during:['',''],
                    price:null,
                    mark:''
                }
	        }
	    },
	    mounted (){
	        var that=this;
            this.host.post('roomWeekPrice',{typeId:this.$route.params.typeId}).then(function(res){
                if(res.isSuccess()){
                    if( typeof res.data()!='undefined' && res.data()!=null){
                        var data=res.data();
                        that.weekPrice={
                            monday: that.getResponsePrice(data.monday),
                            tuesday: that.getResponsePrice(data.tuesday),
                            wensday: that.getResponsePrice(data.wensday),
                            thursday: that.getResponsePrice(data.thursday),
                            friday: that.getResponsePrice(data.friday),
                            saturday: that.getResponsePrice(data.saturday),
                            sunday: that.getResponsePrice(data.sunday)
                        }
                    }
                }else{
                    this.$Notice.info({
                        title: '错误提示',
                        desc: res.error()
                    })
                }
            })
            this.refreshDayPrices()
	    },
	    methods:{
	        getRequestPrice(value){
	            if(value==null || value==''){
	                return -1;
	            }else{
	                value=parseInt(value);
	                return value<0?-1:value;
	            }
	        },
	        getResponsePrice(value){
	            return value<0?'':value
	        },
	        submitWeekPrice: function(){
	            var params={
	                typeId: this.$route.params.typeId,
                    monday: this.getPrice(this.weekPrice.monday),
                    tuesday: this.getPrice(this.weekPrice.tuesday),
                    wensday: this.getPrice(this.weekPrice.wensday),
                    thursday: this.getPrice(this.weekPrice.thursday),
                    friday: this.getPrice(this.weekPrice.friday),
                    saturday: this.getPrice(this.weekPrice.saturday),
                    sunday: this.getPrice(this.weekPrice.sunday)
	            }
	            this.host.post('roomWeekPriceSave',params).then(function(res){
	                if(res.isSuccess()){
	                    this.$Notice.info({
                            title: '提示',
                            desc: '价格设置成功'
                        });
	                }else{
	                    this.$Notice.info({
                            title: '提示',
                            desc: res.error()
                        });
	                }
	            })
	        },
	        pageTo(page){
	            this.page=page;
	            this.refreshDayPrices();
	        },
	        refreshDayPrices(){
	            var that=this;
	            console.log(this.filter.searchDate)
	            var params={
	                typeId:this.$route.params.typeId,
	                page:this.filter.page,
	                pageSize:this.filter.pageSize,
	                searchDate:this.getTimeStamp(this.filter.searchDate)
	            };
                this.host.post('roomDayPrices',params).then(function(res){
                    if(res.isSuccess()){
                        that.totalCount=res.data().totalCount
                        that.dayPrices=res.data().list;
                    }else{
                        this.$Notice.info({
                            title: '错误提示',
                            desc: res.error()
                        })
                    }
                })
	        },
	        refreshDayPrice(id){
	            var that=this;
                this.host.post('roomDayPrice',{typeId:this.$route.params.typeId,id:id}).then(function(res){
                    if(res.isSuccess()){
                        that.dayPrice={
                            id:res.data().id,
                            during:[res.data().startDate,res.data().endDate],
                            price:res.data().price,
                            mark:res.data().mark
                        }
                    }else{
                        this.$Notice.info({
                            title: '错误提示',
                            desc: res.error()
                        })
                    }
                })
	        },
	        deleteDayPrice(id){
	            var that=this;
                this.host.post('roomDayPriceDel',{typeId:this.$route.params.typeId,id:id}).then(function(res){
                    if(res.isSuccess()){
                        that.refreshDayPrices();
                    }else{
                        this.$Notice.info({
                            title: '错误提示',
                            desc: res.error()
                        })
                    }
                })
	        },
	        getTimeStamp(date){
	            if(date){
	                return Math.floor(Date.parse(new Date(date))/1000);
	            }else{
	                return 0;
                }
	        },
	        addDayPrice(){
	            var that=this;
	            var params={
	                id:this.dayPrice.id,
                    startDate:this.getTimeStamp(this.dayPrice.during[0]),
                    endDate:this.getTimeStamp(this.dayPrice.during[1]),
                    price:this.dayPrice.price,
                    mark:this.dayPrice.mark,
                    typeId:this.$route.params.typeId
	            };
                this.host.post('roomDayPriceSet',params).then(function(res){
                    if(res.isSuccess()){
                        that.clearDayPriceForm();
                        that.refreshDayPrices();
                    }else{
                        this.$Notice.info({
                            title: '错误提示',
                            desc: res.error()
                        })
                    }
                })
	        },
	        clearDayPriceForm(){
	            this.dayPrice={
                    id:0,
                    during:['',''],
                    price:null,
                    mark:null
                }
	        }
	    },
        watch:{
            'filter.searchDate'(){
                this.refreshDayPrices();
            }
        }
	}
</script>