<style scoped>
.order-info{
	line-height: 40px;
	font-size: 14px;
	border-right: 1px solid #dddee1;
	span{
		color: #ff9900;
		font-weight: bolder;
		font-size: 18px;
	}
}
</style>

<template>
<div class="layout-body">
	<div class="container">
		<Button type="ghost" @click="goBack"><i class="fa fa-chevron-left icon-mr" aria-hidden="true"></i>返回</Button>
		<div class="mb"></div>
		<Row>
			<Col span="4">
			    <Row :gutter="16" class="order-info">
			    	<Col span="12" class="tr">房间类型：</Col>
			    	<Col span="12">豪华大床房</Col>
			    	<Col span="12" class="tr">房间号：</Col>
			    	<Col span="12">201</Col>
			    	<Col span="12" class="tr">房间单价：</Col>
			    	<Col span="12"><span>￥168.00</span></Col>
			    	<Col span="12" class="tr">订单金额：</Col>
			    	<Col span="12"><span>￥168.00</span></Col>
			    	<Col span="12" class="tr">已收金额：</Col>
			    	<Col span="12"><span>￥168.00</span></Col>
			    	<Col span="12" class="tr">需补金额：</Col>
			    	<Col span="12"><span>￥00.00</span></Col>
			    </Row>
			</Col>
			<Col span="10" offset="1">
				<Form label-position="top">
					<FormItem label="入住人信息：">
						<Row :gutter="8">
							<Col span="10"><Input placeholder="手机号"></Input></Col>
							<Col span="10"><Input placeholder="姓名"></Input></Col>
						</Row>
						<Row :gutter="8" class="mt" v-for="i in person">
							<Col span="10"><Input placeholder="手机号"></Input></Col>
							<Col span="10"><Input placeholder="姓名"></Input></Col>
							<Col span="4" class="tr">
								<Button type="text" @click="deletePerson">
									<i class="fa fa-trash icon-mr" aria-hidden="true"></i>删除
								</Button>
							</Col>
						</Row>
						<Row class="mt">
							<Col span="24">
								<Button type="dashed" long @click="addPerson">
									<i class="fa fa-plus icon-mr" aria-hidden="true"></i>添加一行
								</Button>
							</Col>
						</Row>				
			        </FormItem>
					<FormItem label="入住情况：">
						<Row :gutter="8">
							<Col span="5">
								<Select placeholder="订单来源">
					                <Option value="beijing">美团</Option>
					                <Option value="shanghai">携程</Option>
					                <Option value="shenzhen">线下</Option>
					            </Select>
							</Col>
							<Col span="5">
								<Select placeholder="订单方式">
					                <Option value="beijing">入住</Option>
					                <Option value="shanghai">预订</Option>
					                <Option value="shenzhen">钟点</Option>
					            </Select>
							</Col>
							<Col span="10">
								<DatePicker format="yyyy/MM/dd" type="daterange" placeholder="选择日期"></DatePicker>
							</Col>
						</Row>
						<Row :gutter="8" class="mt" v-for="i in info">
							<Col span="5">
								<Select placeholder="订单来源">
					                <Option value="beijing">美团</Option>
					                <Option value="shanghai">携程</Option>
					                <Option value="shenzhen">线下</Option>
					            </Select>
							</Col>
							<Col span="5">
								<Select placeholder="订单方式">
					                <Option value="beijing">入住</Option>
					                <Option value="shanghai">预订</Option>
					                <Option value="shenzhen">钟点</Option>
					            </Select>
							</Col>
							<Col span="10">
								<DatePicker format="yyyy/MM/dd" type="daterange" placeholder="选择日期"></DatePicker>
							</Col>
							<Col span="4" class="tr">
								<Button type="text" @click="deleteInfo">
									<i class="fa fa-trash icon-mr" aria-hidden="true"></i>删除
								</Button>
							</Col>
						</Row>
						<Row class="mt">
							<Col span="24">
								<Button type="dashed" long @click="addInfo">
									<i class="fa fa-plus icon-mr" aria-hidden="true"></i>添加一行
								</Button>
							</Col>
						</Row>
			        </FormItem>
					<FormItem label="费用情况：">
						<Row :gutter="8">
							<Col span="5">
								<Select placeholder="付费项">
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
								<Input placeholder="￥"></Input>
							</Col>
						</Row>
						<Row :gutter="8" class="mt" v-for="i in money">
							<Col span="5">
								<Select placeholder="付费项">
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
								<Input placeholder="￥"></Input>
							</Col>
							<Col span="4" class="tr">
								<Button type="text" @click="deleteMoney">
									<i class="fa fa-trash icon-mr" aria-hidden="true"></i>删除
								</Button>
							</Col>
						</Row>
						<Row class="mt">
							<Col span="24">
								<Button type="dashed" long @click="addMoney">
									<i class="fa fa-plus icon-mr" aria-hidden="true"></i>添加一行
								</Button>
							</Col>
						</Row>
			        </FormItem>
					<FormItem label="备注：">
			            <Input type="textarea" :rows="5"></Input>
			        </FormItem>
					<FormItem>
			            <Button type="primary" @click="goBack">确认</Button>
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
			info:0,
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
		addInfo(){
			this.info++;
		},
		addMoney(){
			this.money++;
		},
		deletePerson(){
			this.person--;
		},
		deleteInfo(){
			this.info--;
		},
		deleteMoney(){
			this.money--;
		}
	}
}
</script>