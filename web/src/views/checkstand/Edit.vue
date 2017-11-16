<style scoped>
.order-info{
	border-right: 1px solid #dddee1;
	padding-left: 24px;
	.ivu-form-item{
		margin-bottom: 16px;
	}
	p{
		font-size: 14px;
		padding-left: 8px;
	}
	span{
		color: #ff9900;
		padding-left: 8px;
		font-weight: bolder;
		font-size: 18px;
		&.strong{
			font-size: 24px;			
		}
	}
}
</style>

<template>
<div class="layout-body">
	<div class="container-body">
		<div class="mb"></div>
		<Row>
			<Col span="4">
			    <Form label-position="top" class="order-info">
					<FormItem label="订单金额："><span>￥168.00</span></FormItem>
					<FormItem label="优惠信息：">
						<p>普通会员生日</p>
						<p>九折优惠</p>
						<p>满100减10</p>
					</FormItem>
					<FormItem label="应收金额："><span class="strong">￥141.20</span></FormItem>
					<FormItem label="已收金额："><span class="strong">￥200.00</span></FormItem>
					<FormItem label="待收金额："><span class="strong">￥-58.80</span></FormItem>
			    </Form>
			</Col>
			<Col span="10" offset="1">
				<Form v-model="orderInfo" label-position="top">
					<FormItem label="入住人：">
						<Row :gutter="8">
							<Col span="4">
								<Select v-model="orderInfo.channel" placeholder="客人来源">
					                <Option value="1">美团</Option>
					                <Option value="2">携程</Option>
					                <Option value="3">艺龙</Option>
					                <Option value="4">同城</Option>
					                <Option value="5">线下</Option>
					            </Select>
							</Col>
							<Col span="8"><Input v-model="orderInfo.guest.mobile" placeholder="手机号"></Input></Col>
							<Col span="8"><Input v-model="orderInfo.guest.name" placeholder="姓名"></Input></Col>
						</Row>
						<Row :gutter="8" class="mt" v-for="(lodger,i,l) in orderInfo.lodgers">
							<Col span="10"><Input v-model="lodger.mobile" placeholder="手机号"></Input></Col>
							<Col span="10"><Input v-model="lodger.name" placeholder="姓名"></Input></Col>
							<Col span="4">
								<Button type="text" @click="deleteLodger(i)">
									<i class="fa fa-trash icon-mr" aria-hidden="true"></i>删除
								</Button>
							</Col>
						</Row>
						<Row class="mt">
							<Col span="20">
								<Button type="dashed" long @click="addLodger">
									<i class="fa fa-plus icon-mr" aria-hidden="true"></i>添加入住人
								</Button>
							</Col>
						</Row>				
			        </FormItem>
					<FormItem label="入住：">
						<Row :gutter="8">
							<Col span="6">
								{{room.number}}&nbsp;({{room.type_name}})
							</Col>
							<Col span="10">
								<Input v-model="orderInfo.price" :placeholder="placeholder"></Input>
							</Col>
						</Row>
						<Row :gutter="8" class="mt">
							<Col span="6">
								{{date}}入住
							</Col>
							<Col span="5">
								<Select placeholder="入住方式" v-model="orderInfo.type" @on-change="timeChooseShow">
					                <Option value="1" v-if="room.allow_hour_room">全日房</Option>
					                <Option value="2">钟点房</Option>
					            </Select>
							</Col>
							<Col span="5" v-show="orderInfo.type==1">
					             <InputNumber v-model="orderInfo.dayNum" :max="360" :min="1" :step="1"></InputNumber><span class="icon-ml">晚</span>
							</Col>
							<Col span="5" v-show="orderInfo.type==2">
					             <TimePicker v-model="orderInfo.hour" type="time" placement="bottom-end" placeholder="时间选择"></TimePicker>
							</Col>
						</Row>
			        </FormItem>
					<FormItem label="消费：">
						<Row :gutter="8" class="mt" v-for="(pay,i,p) in orderInfo.pay">
							<Col span="5">
								<Select v-model="pay.expenseItem" placeholder="消费项">
					                <Option value="1">收取房费</Option>
					                <Option value="2">收取订金</Option>
					                <Option value="3">收取押金</Option>
					                <Option value="4">退还房费</Option>
					                <Option value="5">退还订金</Option>
					                <Option value="6">退还押金</Option>
					            </Select>
							</Col>
							<Col span="5">
								<Select v-model="pay.channel" placeholder="消费方式">
					                <Option value="1">现金</Option>
					                <Option value="2">支付宝</Option>
					                <Option value="3">微信</Option>
					            </Select>
							</Col>
							<Col span="10">
								<Input v-model="pay.amount" placeholder="付费金额"></Input>
							</Col>
							<Col span="4">
								<Button type="text" @click="deletePay(i)">
									<i class="fa fa-trash icon-mr" aria-hidden="true"></i>删除
								</Button>
							</Col>
						</Row>
						<Row class="mt">
							<Col span="20">
								<Button type="dashed" long @click="addPay">
									<i class="fa fa-plus icon-mr" aria-hidden="true"></i>添加消费
								</Button>
							</Col>
						</Row>
			        </FormItem>
					<FormItem label="备注：">
						<Row>
							<Col span="20">
				            	<Input v-model="orderInfo.mark" type="textarea" :rows="5"></Input>
							</Col>
						</Row>
			        </FormItem>
					<FormItem>
						<!-- 只有在当日才能办理入住 -->
			            <Button type="primary" @click="occupancy">确认入住</Button>
			            <Button type="warning" @click="reverse" class="icon-ml">确认预订</Button>
                        <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
			        </FormItem>
			    </Form>
			</Col>
		</Row>
	</div>
</div>
</template>

<script>
export default{
	data () {
		return {
			room:{},
			date:'',
			placeholder:'',
			orderInfo: {
			    type: 1,
			    guest:{mobile: '',name: ''},
			    lodgers: [],
			    pay: [{amount: '',channel: '',expenseItem:''}]
			},
			timepick: false
		}
	},
	mounted(){
	    var that=this;
	    this.host.post('merchantRoom',{id: this.$route.params.id}).then(function(res){
	        if(res.isSuccess()){
	            that.room=res.data().room;
	            that.date=res.data().date;
	            that.placeholder='原价：'+that.room.default_price;
	        }else{
	            this.$Notice.info({
	                title: '错误提示',
	                desc: res.error()
	            })
	        }
	    })
	},
	methods:{
		goBack(){
			history.go(-1);
		},
		addLodger(){
            this.orderInfo.lodgers.push({mobile: '',name: ''})
		},
		deleteLodger(index){
			this.orderInfo.lodgers.splice(index,1);
		},
		addPay(){
            this.orderInfo.pay.push({amount: '',channel: '',expenseItem:''});
        },
		deletePay(index){
            this.orderInfo.pay.splice(index,1);
		},
		timeChooseShow(){
			if(this.orderInfo.type==1) this.timepick=true;
			else this.timepick=false;
		},
		occupancy (){
		    var number;
		    if(this.orderInfo.type==1){
                number=this.orderInfo.dayNum;
		    }else if(this.orderInfo.type==2){
                number=Math.floor(Date.parse(new Date(this.orderInfo.hour))/1000)%86400;
		    }else{
		        this.$Notice.info({
		            title: '错误提示',
		            desc: '请选择类型'
		        })
		        return ;
		    }
		    var param={
		        lodgers: this.orderInfo.lodgers,
		        guest: this.orderInfo.guest,
		        roomId: this.$route.params.id,
		        price: this.orderInfo.price,
		        mark: this.orderInfo.mark,
		        pay: this.orderInfo.pay,
		        type: this.orderInfo.type,
		        number:number,
		        channel: this.orderInfo.channel
		    };
		    this.host.post('merchantOccupancy',param).then(function(res){
		        if($res.isSuccess()){
		            this.$router.push('/admin/checkstand');
		        }else{
		            this.$Notice.info({
		                title: '错误提示',
		                desc: res.error()
		            })
		        }
		    })
		},
		reverse (){

		}
	}
}
</script>