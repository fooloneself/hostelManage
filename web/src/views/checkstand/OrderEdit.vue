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
		padding-left: 24px;
	}
	span{
		color: #ff9900;
		font-weight: bolder;
		font-size: 18px;
		padding-left: 24px;
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
				<FormItem label="订单总价："><span>￥168.00</span></FormItem>
				<FormItem label="优惠活动：">
					<p>九折优惠</p>
				</FormItem>
				<FormItem label="应收金额："><span class="strong">￥141.20</span></FormItem>
				<FormItem label="已收金额："><span class="strong">￥200.00</span></FormItem>
				<FormItem label="待收金额："><span class="strong">￥-58.80</span></FormItem>
		    </Form>
		</Col>
		<Col span="20">
			<Form label-position="top" style="margin-left: 24px;">
				<FormItem label="预订人信息">
					<Row :gutter="8">
						<Col span="6">
							<Select placeholder="客人来源">
				                <Option value="0">来源一</Option>
				            </Select>
						</Col>
						<Col span="7"><Input placeholder="预订人姓名"></Input></Col>
						<Col span="7">
							<Input placeholder="手机号">
								<Button slot="append" @click="checkMember">查询会员信息</Button>
							</Input>
						</Col>
					</Row>
					<Row>
						<Col span="20">
							<Table v-if="showMember" size="small" class="mt" :columns="member.columns" :data="member.data" stripe></Table>
						</Col>
					</Row>
		        </FormItem>
				<FormItem label="预订房信息">
					<Row :gutter="8" class="mt" v-for="i in room">
						<!--需要监听当前选择日期是否还有可订的房型-->
						<Col span="6">
							<DatePicker type="daterange" placeholder="预订时间" style="width: 100%"></DatePicker>
						</Col>
						<Col span="7">
							<Select placeholder="房型">
				                <Option value="单间">单间</Option>
				                <Option value="标准">标准</Option>
				                <Option value="豪华">豪华</Option>
				            </Select>
						</Col>
						<Col span="7">
							<Select placeholder="房号">
				                <Option value="201">201</Option>
				                <Option value="202">202</Option>
				                <Option value="203">203</Option>
				            </Select>
						</Col>
						<Col span="4">
							<Button v-if="i==room" type="text" @click="addRoom">
								<i class="fa fa-plus fa-fw icon-mr" aria-hidden="true"></i>
								添加房间
							</Button>
							<Button v-else type="text" @click="deleteRoom">
								<i class="fa fa-trash fa-fw icon-mr" aria-hidden="true"></i>
								删除房间
							</Button>
						</Col>
					</Row>
		        </FormItem>
				<FormItem label="收费信息">
					<Row :gutter="8">
						<Col span="10">
							<Input placeholder="订单总价：￥100.00"></Input>
						</Col>
						<Col span="10">
							<Select placeholder="请选择优惠活动">
				                <Option value="-1">不参与活动</Option>
				                <Option value="0">优惠活动二</Option>
				                <Option value="1">优惠活动三</Option>
				            </Select>
						</Col>
					</Row>
					<Row :gutter="8" class="mt" v-for="i in money">
						<Col span="5">
							<Select placeholder="收费项">
				                <Option value="1">收取房费</Option>
				                <Option value="2">收取定金</Option>
				                <Option value="3">收取押金</Option>
				                <Option value="4">退还房费</Option>
				                <Option value="5">退还定金</Option>
				                <Option value="6">退还押金</Option>
				            </Select>
						</Col>
						<Col span="5">
							<Select placeholder="付费方式">
				                <Option value="beijing">现金</Option>
				                <Option value="shanghai">支付宝</Option>
				                <Option value="shenzhen">微信</Option>
				            </Select>
						</Col>
						<Col span="10">
							<Input placeholder="付费金额"></Input>
						</Col>
						<Col span="4">
							<Button type="text" @click="deleteMoney">
								<i class="fa fa-trash fa-fw icon-mr" aria-hidden="true"></i>删除收费
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
							<Button type="text" @click="addMoney">
								<i class="fa fa-plus fa-fw icon-mr" aria-hidden="true"></i>添加收费
							</Button>
						</Col>
					</Row>
		        </FormItem>
				<FormItem label="备注信息">
					<Row>
						<Col span="20">
			            	<Input type="textarea" :rows="5"></Input>
						</Col>
					</Row>
		        </FormItem>
				<FormItem>
		            <Button type="primary" v-if="$route.params.id==0">确认预订</Button>
		            <Button type="primary" v-else>确认修改</Button>
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
			money: 0,
	        room: 2,
			showMember:false,
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
	            data: [{name:'李波美',phone:'13800138000',rank:'非会员',price:'￥0.00'}]
	        }
		}
	},
	methods:{
		goBack(){
			history.go(-1);
		},
		checkMember(){
			this.showMember = true;
		},
		addRoom(){
			this.room++;
		},
		deleteRoom(){
			this.room--;
		},
		addMoney(){
			this.money++;
		},
		deleteMoney(){
			this.money--;
		},
	}
}
</script>