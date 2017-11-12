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
				<Form label-position="top">
					<FormItem label="入住人：">
						<Row :gutter="8">
							<Col span="4">
								<Select placeholder="客人来源">
					                <Option value="1">美团</Option>
					                <Option value="2">携程</Option>
					                <Option value="3">艺龙</Option>
					                <Option value="4">同城</Option>
					                <Option value="5">线下</Option>
					            </Select>
							</Col>
							<Col span="8"><Input placeholder="手机号"></Input></Col>
							<Col span="8"><Input placeholder="姓名"></Input></Col>
						</Row>
						<Row :gutter="8" class="mt" v-for="i in person">
							<Col span="10"><Input placeholder="手机号"></Input></Col>
							<Col span="10"><Input placeholder="姓名"></Input></Col>
							<Col span="4">
								<Button type="text" @click="deletePerson">
									<i class="fa fa-trash icon-mr" aria-hidden="true"></i>删除
								</Button>
							</Col>
						</Row>
						<Row class="mt">
							<Col span="20">
								<Button type="dashed" long @click="addPerson">
									<i class="fa fa-plus icon-mr" aria-hidden="true"></i>添加入住人
								</Button>
							</Col>
						</Row>				
			        </FormItem>
					<FormItem label="入住：">
						<Row :gutter="8">
							<Col span="6">
								201&nbsp;(豪华大床房)
							</Col>
							<Col span="10">
								<Input placeholder="原房价：￥168.00"></Input>
							</Col>
						</Row>
						<Row :gutter="8" class="mt">
							<Col span="6">
								2017/11/11入住
							</Col>
							<Col span="5">
								<Select placeholder="入住方式" v-model="orderType" @on-change="timeChooseShow">
					                <Option value="1">日租房</Option>
					                <Option value="2">钟点房</Option>
					            </Select>
							</Col>
							<Col span="5" v-show="timepick">
					             <InputNumber :max="360" :min="1" :step="1"></InputNumber><span class="icon-ml">晚</span>
							</Col>
							<Col span="5" v-show="!timepick">
					             <TimePicker type="time" placement="bottom-end" placeholder="时间选择"></TimePicker>
							</Col>
						</Row>
			        </FormItem>
					<FormItem label="消费：">
						<Row :gutter="8">
							<Col span="5">
								<Select placeholder="付费项">
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
					                <Option value="1">现金</Option>
					                <Option value="2">支付宝</Option>
					                <Option value="3">微信</Option>
					                <Option value="3">银联</Option>
					            </Select>
							</Col>
							<Col span="10">
								<Input placeholder="付费金额"></Input>
							</Col>
						</Row>
						<Row :gutter="8" class="mt" v-for="i in money">
							<Col span="5">
								<Select placeholder="消费项">
					                <Option value="1">收取房费</Option>
					                <Option value="2">收取订金</Option>
					                <Option value="3">收取押金</Option>
					                <Option value="4">退还房费</Option>
					                <Option value="5">退还订金</Option>
					                <Option value="6">退还押金</Option>
					            </Select>
							</Col>
							<Col span="5">
								<Select placeholder="消费方式">
					                <Option value="1">现金</Option>
					                <Option value="2">支付宝</Option>
					                <Option value="3">微信</Option>
					            </Select>
							</Col>
							<Col span="10">
								<Input placeholder="付费金额"></Input>
							</Col>
							<Col span="4">
								<Button type="text" @click="deleteMoney">
									<i class="fa fa-trash icon-mr" aria-hidden="true"></i>删除
								</Button>
							</Col>
						</Row>
						<Row class="mt">
							<Col span="20">
								<Button type="dashed" long @click="addMoney">
									<i class="fa fa-plus icon-mr" aria-hidden="true"></i>添加消费
								</Button>
							</Col>
						</Row>
			        </FormItem>
					<FormItem label="备注：">
						<Row>
							<Col span="20">
				            	<Input type="textarea" :rows="5"></Input>
							</Col>
						</Row>
			        </FormItem>
					<FormItem>
			            <Button type="primary" @click="goBack">确认入住</Button>
			            <Button type="warning" @click="goBack" class="icon-ml">确认预订</Button>
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
			person:0,
			timepick:true,
			orderType:'1',
			money:0
		}
	},
	methods:{
		goBack(){
			history.go(-1);
		},
		addPerson(){
			this.person++;
		},
		addMoney(){
			this.money++;
		},
		deletePerson(){
			this.person--;
		},
		deleteMoney(){
			this.money--;
		},
		timeChooseShow(){
			if(this.orderType==1) this.timepick=true;
			else this.timepick=false;
		}
	}
}
</script>