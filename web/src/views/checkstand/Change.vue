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
<div class="layout-body">
	<div class="container-body">
		<Row>
			<Col span="4">
			    <Form label-position="top" class="order-info">
					<FormItem label="订单总价："><span>￥{{order.amount}}</span></FormItem>
                    <FormItem label="优惠活动：">
                        <p>九折优惠</p>
                    </FormItem>
                    <FormItem label="应收金额："><span class="strong">￥{{order.amount_payable}}</span></FormItem>
                    <FormItem label="已收金额："><span class="strong">￥{{order.amount_paid}}</span></FormItem>
                    <FormItem label="待收金额："><span class="strong">￥{{order.amount_deffer}}</span></FormItem>
			    </Form>
			</Col>
			<Col span="20">
				<Form label-position="top" style="margin-left: 24px;">
					<FormItem label="原房信息">
						<Row :gutter="8">
							<Col span="5">
								<span class="extra">入住方式：</span>{{order.type}}
							</Col>
							<Col span="5">
					            <span class="extra">入住时长：</span>{{order.quantity}}
							</Col>
						</Row>
						<Table size="small" :columns="room.columns" :data="room.data" stripe></Table>
			        </FormItem>
					<FormItem v-model="form" label="换房操作">
						<Row :gutter="8">
							<Col span="5">
								<Select v-model="form.roomType" placeholder="房间类型">
								    <Option v-for="(roomType,rt) in roomTypes" :value="roomType.id">{{roomType.name}}</Option>
					            </Select>
							</Col>
							<Col span="5">
								<Select v-model="form.roomId" placeholder="房间号">
								    <Option v-for="(room,rm) in rooms" :value="room.id">{{room.number}}</Option>
					            </Select>
							</Col>
							<Col span="5">
								<Input v-model="form.totalAmount" :placeholder="order.total_amount">
									<span slot="prepend">订单总价</span>
								</Input>
							</Col>
							<Col span="5">
								<Select v-model="form.activeId" placeholder="优惠活动">
					                <Option value="-1">请选择活动</Option>
					            </Select>
							</Col>
						</Row>
						<Row :gutter="8" class="mt" v-for="(pay,py) in form.pays">
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
								<Button v-if="py==(form.pays.length-1)" type="text" @click="addPay">
									<i class="fa fa-plus icon-mr" aria-hidden="true"></i>添加收费
								</Button>
								<Button v-if="py!=(form.pays.length-1)" type="text" @click="deletePay(py)">
                                    <i class="fa fa-plus icon-mr" aria-hidden="true"></i>删除收费
                                </Button>
							</Col>
						</Row>
			        </FormItem>
					<FormItem label="备注信息">
						<Row>
							<Col span="24">
				            	<Input type="textarea" :rows="5"></Input>
							</Col>
						</Row>
					</FormItem>
					<FormItem>
			            <Button type="primary" @click="changeRoom">确认换房</Button>
                        <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
			        </FormItem>
					<FormItem label="入住人信息">
						<Row :gutter="8">
							<Col span="5">
								<span class="extra">客人来源：</span>{{order.channel_name}}
							</Col>
						</Row>
						<Table size="small" :columns="member.columns" :data="member.data" stripe></Table>
			        </FormItem>
					<FormItem label="收费情况">
						<Table size="small" :columns="cost.columns" :data="cost.data" stripe></Table>
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
			money:0,
			member: {
				columns: [
	                {
                        title: '姓名',
                        key: 'person_name'
                    },
                    {
                        title: '手机号',
                        key: 'mobile'
                    },
                    {
                        title: '入住时间',
                        key: 'occupancy_date'
                    },
                    {
                        title: '会员等级',
                        key: 'rank_name'
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
	                    key: 'amount'
	                }
	            ],
	            data: []
	        },
			cost: {
				columns: [
	                {
	                    title: '收费项',
	                    key: 'expense_name'
	                },
	                {
	                    title: '付费方式',
	                    key: 'channel_name'
	                },
	                {
	                    title: '付费金额',
	                    key: 'amount'
	                }
	            ],
	            data: []
	        },
	        paymentChannels:[],
            expanseItems:[],
	        order:{},
	        roomTypes:[],
	        rooms:[],
	        form:{
                roomType:0,
                roomId:0,
                totalAmount:null,
                activeId:0,
                pays:[{amount: '',channel: '',expenseItem:''}],
                mark:''
	        }
		}
	},
	mounted(){
        var that=this;
        this.host.post('merchantOrderInfo',{orderId:this.$route.params.orderId,roomId:this.$route.params.roomId}).then(function(res){
            if(res.isSuccess()){
                that.order=res.data().order;
                that.form.roomType=that.order.room_type;
                that.member.data=res.data().occupancyRecord;
                that.room.data=res.data().costRecord;
                that.cost.data=res.data().payRecord;
            }else{
                this.$Notice.info({
                    title:'提示',
                    desc:res.error()
                });
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
        this.host.post('merchantAllRoomType').then(function(res){
            if(res.isSuccess()){
                that.roomTypes=res.data();
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
		addPay(){
			this.form.pays.push({amount: '',channel: '',expenseItem:''});
		},
		deletePay(index){
			this.form.pays.splice(index,1);
		},
		turnUrl(url,query){
            this.$router.push(url)
        },
        changeRoom(){
            var params={
                fromRoomId:this.$route.params.roomId,
                toRoomId:this.form.roomId,
                orderId:this.$route.params.orderId,
                totalAmount:this.form.totalAmount?this.form.totalAmount:-1,
                pays:this.form.pays,
                activeId:this.form.activeId,
                mark:this.form.mark
            };
            this.host.post('merchantOrderConvertRoom',params).then(function(res){
                if(res.isSuccess()){
                    this.$router.push('/admin/checkstandView/'+params.toRoomId+'/'+params.orderId);
                }else{
                    this.$Notice.info({
                        title: '错误提示',
                        desc: res.error()
                    })
                }
            })
        },
        refreshRoomList(){
            var that=this;
            this.host.post('merchantRoomListOfType',{type: this.form.roomType}).then(function(res){
                if(res.isSuccess()){
                    that.rooms=res.data();
                }else{
                    this.$Notice.info({
                        title: '错误提示',
                        desc: res.error()
                    })
                }
            })
        }
	},
	watch:{
	    'form.roomType':'refreshRoomList'
	}
}
</script>