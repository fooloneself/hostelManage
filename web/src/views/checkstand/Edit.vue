<style scoped>
.order-info{
	background: #f8f8f9;
	border: 1px solid #dddee1;
	padding: 24px;
	border-radius: 5px;
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
span.extra{
	color: #9ea7b4;
}
</style>

<template>
<div>
	<Row>
		<Col span="4">
		    <Form label-position="top" class="order-info">
				<FormItem label="订单总价："><span>￥{{defaultTotalAmount}}</span></FormItem>
				<FormItem label="优惠活动：">
					<p>{{selectedActive}}</p>
				</FormItem>
				<FormItem label="应收金额："><span class="strong">￥{{defaultTotalAmount*0.9}}</span></FormItem>
				<FormItem label="已收金额："><span class="strong">￥200.00</span></FormItem>
				<FormItem label="待收金额："><span class="strong">￥{{defaultTotalAmount*0.9-200}}</span></FormItem>
		    </Form>
		</Col>
		<Col span="20">
			<Form v-model="orderInfo" label-position="top" style="margin-left: 24px;">
				<FormItem label="入住人信息">
					<Row :gutter="8">
						<Col span="4">
							<Select v-model="orderInfo.channel" placeholder="客人来源">
				                <Option v-for="(channel,c) in channels" :value="channel.id">{{channel.name}}</Option>
				            </Select>
						</Col>
						<Col span="8"><Input v-model="orderInfo.name" placeholder="姓名"></Input></Col>
						<Col span="8">
						<Input v-model="orderInfo.mobile" placeholder="手机号">
							<Button slot="append" @click="checkMember">检索会员</Button>
						</Input>
						</Col>
					</Row>
					<Row :gutter="8">
						<Col span="20">
							<Table v-if="showMember" size="small" class="mt" :columns="member.columns" :data="member.data" stripe></Table>
						</Col>
					</Row>
					<Row :gutter="8" class="mt" v-for="(lodger,i,l) in orderInfo.lodgers">
						<Col span="10"><Input v-model="lodger.name" placeholder="姓名"></Input></Col>
						<Col span="10"><Input v-model="lodger.mobile" placeholder="手机号"></Input></Col>
						<Col span="4">
							<Button v-if="i==orderInfo.lodgers.length-1" type="text" @click="addLodger">
								<i class="fa fa-plus fa-fw icon-mr" aria-hidden="true"></i>添加入住人
							</Button>
							<Button v-else type="text" @click="deleteLodger(i)">
								<i class="fa fa-trash fa-fw icon-mr" aria-hidden="true"></i>删除入住人
							</Button>
						</Col>
					</Row>
		        </FormItem>
				<FormItem label="入住房信息">
					<Row :gutter="8">
						<Col span="4">
							<Select placeholder="入住方式" v-model="orderInfo.type" @on-change="timeChooseShow">
				                <Option :value="1">全天房</Option>
				                <Option :value="2" v-if="room.allow_hour_room">钟点房</Option>
				            </Select>
						</Col>
						<Col span="4">
				             <InputNumber class="icon-mr" v-model="orderInfo.quantity" :max="360" :min="1" :step="1"></InputNumber>天
						</Col>
						<Col span="4" v-show="false">
				             <TimePicker v-model="orderInfo.hour" type="time" placement="bottom-end" placeholder="入住时间选择"></TimePicker>
						</Col>
					</Row>
					<Row :gutter="8" class="mt">
						<Col span="20"><Table size="small" :columns="room.columns" :data="room.data" stripe></Table></Col>
					</Row>
		        </FormItem>
				<FormItem label="收费信息">
					<Row :gutter="8">
						<Col span="10">
							<Input v-model="orderInfo.totalAmount" :placeholder="getTotalAmountPlaceholder()">
								<span slot="prepend">订单总价</span>
							</Input>
						</Col>
						<Col span="10">
							<Select v-model="orderInfo.activityId" placeholder="优惠活动">
				                <Option value="0">请选择活动</Option>
				                <Option v-for="(activity,a) in activities" :value="activity.id">{{activity.name}}</Option>
				            </Select>
						</Col>
					</Row>
					<Row :gutter="8" v-for="(pay,p) in orderInfo.pay" class="mt">
						<Col span="5">
							<Select v-model="pay.expenseItem" placeholder="收费项">
				                <Option v-for="(expanseItem,ei) in expanseItems" :value="expanseItem.key">{{expanseItem.value}}</Option>
				            </Select>
						</Col>
						<Col span="5">
							<Select v-model="pay.channel" placeholder="付费方式">
				                <Option v-for="(paymentChannel,pc) in paymentChannels" :value="paymentChannel.key">{{paymentChannel.value}}</Option>
				            </Select>
						</Col>
						<Col span="10">
							<Input v-model="pay.amount" placeholder="付费金额"></Input>
						</Col>
						<Col span="4">
							<Button v-show="(orderInfo.pay.length-1)!=p" type="text" @click="deletePay(p)">
								<i class="fa fa-trash fa-fw icon-mr" aria-hidden="true"></i>删除收费
							</Button>
							<Button v-show="(orderInfo.pay.length-1)==p" type="text" @click="addPay">
                                <i class="fa fa-plus icon-mr fa-fw" aria-hidden="true"></i>添加收费
                            </Button>
						</Col>
					</Row>
		        </FormItem>
				<FormItem label="备注信息">
					<Row>
						<Col span="20">
			            	<Input v-model="orderInfo.mark" type="textarea" :rows="5"></Input>
						</Col>
					</Row>
		        </FormItem>
				<FormItem>
					<!-- 新添订单：只有在当日才能办理入住 -->
		            <Button type="primary" @click="occupancy">确认入住</Button>
                    <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
		        </FormItem>
		    </Form>
		</Col>
	</Row>
</div>
</template>

<script>
export default{
	data () {
		return {
			showMember: false,
			member: {
				columns: [
	                {
	                    title: '会员姓名',
	                    key: 'name'
	                },
	                {
	                    title: '手机号',
	                    key: 'mobile'
	                },
	                {
	                    title: '会员等级',
	                    key: 'rank'
	                },
                    {
                        title: '操作',
                        key: 'action',
                        fixed: 'right',
                        width: 100,
                        render: (h, params) => {
                            var item=[];
                            if(!params.row.isMember){
                                item.push(h('Button', {
                                     props: {
                                         type: 'text',
                                         size: 'small'
                                     },
                                     on: {
                                         click: ()=>{
                                             this.$router.push('/admin/memberListEdit/0')
                                         }
                                     }
                                }, '加入会员'));
                            }
                            return h('div', item);
                        }
                    }
	            ],
	            data: []
	        },
	        room: {
				columns: [
	                {
	                    title: '入住时间',
	                    key: 'date'
	                },
	                {
	                    title: '房型',
	                    key: 'typeName'
	                },
	                {
	                    title: '房号',
	                    key: 'number'
	                },
	                {
	                    title: '单价',
	                    key: 'amount'
	                }
	            ],
	            data: []
	        },
			date:'',
			channels:[],
			paymentChannels:[],
			expanseItems:[],
			timepick: false,
			defaultTotalAmount:0,
			activities:[],
			selectedActive:'无',
			orderInfo: {
			    channel:null,
			    mobile:null,
			    name:null,
			    lodgers: [{mobile: '',name: ''}],
                type: 1,
                quantity: 1,
                totalAmount:null,
                activeId:0,
                pay: [{expenseItem:null,channel:null,amount:null}],
                mark:''
            }
		}
	},
	mounted(){
	    var that=this;
	    this.host.post('channelAll').then(function(res){
            if(res.isSuccess()){
                that.channels=res.data();
            }else{
                this.$Notice.info({
                    title: '错误提示',
                    desc: res.error()
                })
            }
        })
        this.host.post('merchantPaymentChannel').then(function(res){
            if(res.isSuccess()){
                that.paymentChannels=res.data();
            }else{
                this.$Notice.info({
                    title: '错误提示',
                    desc: res.error()
                })
            }
        })
        this.host.post('merchantExpanseItem').then(function(res){
            if(res.isSuccess()){
                that.expanseItems=res.data();
            }else{
                this.$Notice.info({
                    title: '错误提示',
                    desc: res.error()
                })
            }
        })
        this.refreshRoomConsumptionList();
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
		checkMember(){
		    if(!this.orderInfo.mobile){
		        this.$Notice.info({
                    title: '错误提示',
                    desc:'请先输入手机号'
                })
                return ;
		    }
			this.showMember = true;
			this.searchMember();
		},
		timeChooseShow(){
			if(this.orderInfo.type==1) this.timepick=true;
			else this.timepick=false;
		},
		occupancy (){
		    this.operate('merchantOccupancy');
		},
		reverse (){
            this.operate('merchantReverse');
		},
		getTotalAmountPlaceholder(){
		    return this.defaultTotalAmount<=0?'总价格':this.defaultTotalAmount;
		},
		operate(action){
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
                roomId: this.$route.params.roomId,
                price: this.orderInfo.price,
                mark: this.orderInfo.mark,
                pay: this.orderInfo.pay,
                type: this.orderInfo.type,
                quantity:this.orderInfo.quantity,
                channel: this.orderInfo.channel,
                name:this.orderInfo.name,
                mobile:this.orderInfo.mobile,
                activityId:this.orderInfo.activeId
            };
            this.host.post(action,param).then(function(res){
                if(res.isSuccess()){
                    this.$router.push('/admin');
                }else{
                    this.$Notice.info({
                        title: '错误提示',
                        desc: res.error()
                    })
                }
            })
		},
		refreshRoomConsumptionList(){
		    var that=this;
		    this.host.post('merchantRoomConsumptionBill',{roomId: this.$route.params.roomId,quantity:this.orderInfo.quantity}).then(function(res){
                if(res.isSuccess()){
                    that.room.data=res.data().list;
                    that.defaultTotalAmount=res.data().totalAmount.toString();
                }else{
                    this.$Notice.info({
                        title: '错误提示',
                        desc: res.error()
                    })
                }
            })
		},
		searchMember(){
		    var that=this;
		    this.host.post('merchantMemberSearch',{mobile:this.orderInfo.mobile}).then(function(res){
		        if(res.isSuccess()){
                    that.member.data=res.data();
		        }else{
		            this.$Notice.info({
		                title: '错误提示',
		                desc:res.error()
		            })
		        }
		    })
		},
		refreshActivityForOrder(){
		    this.host.post('merchantOrderActivity',this.orderInfo).then(function(res){
                if(res.isSuccess()){
                    that.activities=res.data();
		        }else{
		            this.$Notice.info({
		                title: '错误提示',
		                desc:res.error()
		            })
		        }
		    })
		}
	},
    watch:{
        'orderInfo.quantity'(){
            this.refreshRoomConsumptionList();
        }
    }
}
</script>