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
				<FormItem label="订单金额："><span>￥168.00</span></FormItem>
				<FormItem label="优惠活动：">
					<p>九折优惠</p>
				</FormItem>
				<FormItem label="应收金额："><span class="strong">￥141.20</span></FormItem>
				<FormItem label="已收金额："><span class="strong">￥200.00</span></FormItem>
				<FormItem label="待收金额："><span class="strong">￥-58.80</span></FormItem>
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
						<Col span="8"><Input v-model="orderInfo.guest.name" placeholder="姓名"></Input></Col>
						<Col span="8"><Input v-model="orderInfo.guest.mobile" placeholder="手机号"></Input></Col>
					</Row>
					<Row :gutter="8">
						<Col span="20">
							<Table class="mt" :columns="member.columns" :data="member.data" stripe></Table>
						</Col>
					</Row>
					<Row :gutter="8" class="mt" v-for="(lodger,i,l) in orderInfo.lodgers">
						<Col span="10"><Input v-model="lodger.name" placeholder="姓名"></Input></Col>
						<Col span="10"><Input v-model="lodger.mobile" placeholder="手机号"></Input></Col>
						<Col span="4">
							<Button type="text" @click="deleteLodger(i)">
								<i class="fa fa-trash icon-mr" aria-hidden="true"></i>删除
							</Button>
						</Col>
					</Row>
					<Row :gutter="8" class="mt">
						<Col span="20">
							<Button type="dashed" long @click="addLodger">
								<i class="fa fa-plus icon-mr" aria-hidden="true"></i>添加其他入住人
							</Button>
						</Col>
					</Row>
		        </FormItem>
				<FormItem label="入住房信息">
					<Row :gutter="8">
						<Col span="20"><Table :columns="room.columns" :data="room.data" stripe></Table></Col>
					</Row>
					<Row :gutter="8" class="mt">
						<Col span="4">
							<Select placeholder="入住方式" v-model="orderInfo.type" @on-change="timeChooseShow">
				                <Option :value="1">全天房</Option>
				                <Option :value="2" v-if="room.allow_hour_room">钟点房</Option>
				            </Select>
						</Col>
						<Col span="4" v-show="orderInfo.type==1">
				             <InputNumber v-model="orderInfo.dayNum" :max="360" :min="1" :step="1"></InputNumber>
						</Col>
						<Col span="4" v-show="orderInfo.type==2">
				             <TimePicker v-model="orderInfo.hour" type="time" placement="bottom-end" placeholder="入住时间选择"></TimePicker>
						</Col>
					</Row>
		        </FormItem>
				<FormItem label="收费信息">
					<Row :gutter="8">
						<Col span="10">
							<Input v-model="orderInfo.price" :placeholder="placeholder"></Input>
						</Col>
						<Col span="10">
							<Select placeholder="优惠活动">
				                <Option value="-1">请选择活动</Option>
				                <Option value="0">优惠活动二</Option>
				                <Option value="1">优惠活动三</Option>
				            </Select>
						</Col>
					</Row>
					<Row :gutter="8" v-for="(pay,i,p) in orderInfo.pay" class="mt">
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
							<Button type="text" @click="deletePay(i)">
								<i class="fa fa-trash icon-mr" aria-hidden="true"></i>删除收费
							</Button>
						</Col>
					</Row>
					<Row :gutter="8" class="mt">
							<Col span="5">
								<Select placeholder="收费项">
					                <Option value="1">收取房费</Option>
					                <Option value="2">收取订金</Option>
					                <Option value="3">收取押金</Option>
					                <Option value="4">退还房费</Option>
					                <Option value="5">退还订金</Option>
					                <Option value="6">退还押金</Option>
					            </Select>
							</Col>
							<Col span="5">
								<Select placeholder="付费方式">
					                <Option value="beijing">余额</Option>
					                <Option value="beijing">现金</Option>
					                <Option value="shanghai">支付宝</Option>
					                <Option value="shenzhen">微信</Option>
					            </Select>
							</Col>
							<Col span="10">
								<Input placeholder="付费金额"></Input>
							</Col>
							<Col span="4">
								<Button type="text" @click="addPay">
									<i class="fa fa-plus icon-mr" aria-hidden="true"></i>添加收费
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
					<!-- 修改订单 -->
		            <Button type="primary">确认修改</Button>
					<!-- 办理入住 -->
		            <Button type="primary" @click="occupancy">确认入住</Button>
		            <!-- <Button type="warning" @click="reverse" class="icon-ml">确认预订</Button> -->
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
			member: {
				columns: [
	                {
	                    title: '会员姓名',
	                    key: 'name'
	                },
	                {
	                    title: '手机号',
	                    key: 'phone'
	                },
	                {
	                    title: '会员等级',
	                    key: 'rank'
	                },
	                {
	                    title: '余额',
	                    key: 'price'
	                }
	            ],
	            data: []
	        },
	        room: {
				columns: [
	                {
	                    title: '预订时间',
	                    key: 'date'
	                },
	                {
	                    title: '房型',
	                    key: 'type_name'
	                },
	                {
	                    title: '房号',
	                    key: 'number'
	                },
	                {
	                    title: '单价',
	                    key: 'default_price'
	                }
	            ],
	            data: []
	        },
			date:'',
			placeholder:'',
			orderInfo: {
			    type: 1,
			    guest:{mobile: '',name: ''},
			    lodgers: [],
			    pay: [],
			    dayNum: 1
			},
			channels:[],
			paymentChannels:[],
			expanseItems:[],
			timepick: false
		}
	},
	mounted(){
	    var that=this;
	    this.host.post('merchantRoom',{id: this.$route.params.id}).then(function(res){
	        if(res.isSuccess()){
	            res.data().room.date=res.data().date;
	            res.data().room.default_price='￥'+res.data().room.default_price;
	            that.room.data.push(res.data().room);
	            that.placeholder='房间总价：'+res.data().room.default_price;
	        }else{
	            this.$Notice.info({
	                title: '错误提示',
	                desc: res.error()
	            })
	        }
	    })
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
		    this.operate('merchantOccupancy');
		},
		reverse (){
            this.operate('merchantReverse');
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
                roomId: this.$route.params.id,
                price: this.orderInfo.price,
                mark: this.orderInfo.mark,
                pay: this.orderInfo.pay,
                type: this.orderInfo.type,
                number:number,
                channel: this.orderInfo.channel
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
		}
	}
}
</script>