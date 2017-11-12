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
span.extra{
	color: #9ea7b4;
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
							<Col span="10">
								<span class="extra">客人来源：</span>美团
							</Col>
							<Col span="10">
								<span class="extra">佣金费用：</span>￥10.00
							</Col>
						</Row>
						<Row :gutter="8">
							<Col span="10"><span class="extra">手机号：</span>13800138000</Col>
							<Col span="10"><span class="extra">姓名：</span>D.Seeyou</Col>
						</Row>		
			        </FormItem>
					<FormItem label="入住：">
						<Row :gutter="8">
							<Col span="5">
								201&nbsp;(豪华大床房)
							</Col>
							<Col span="5">
								<span class="extra">原房价：</span>￥168.00
							</Col>
							<Col span="10">
								<span class="extra">入住房价：</span>￥168.00
							</Col>
						</Row>
						<Row :gutter="8">
							<Col span="5">
								2017/11/11入住
							</Col>
							<Col span="5">
								<span class="extra">入住方式：</span>日租房
							</Col>
							<Col span="10">
					            <span class="extra">入住时长：</span>2晚
							</Col>
						</Row>
			        </FormItem>
					<FormItem label="换房：">
						<Row :gutter="8">
							<Col span="5">
								<Select placeholder="房间类型">
					                <Option value="beijing">单间</Option>
					                <Option value="shanghai">标准</Option>
					                <Option value="shenzhen">豪华</Option>
					            </Select>
							</Col>
							<Col span="5">
								<Select placeholder="房间号">
					                <Option value="beijing">201</Option>
					                <Option value="shanghai">202</Option>
					                <Option value="shenzhen">203</Option>
					            </Select>
							</Col>
							<Col span="10">
								<Input placeholder="原房价：￥168.00"></Input>
							</Col>
						</Row>
			        </FormItem>
					<FormItem label="费用：">
						<Row :gutter="8" v-for="i in 3">
							<Col span="5">
								<span class="extra">付费项：</span>收取房费
							</Col>
							<Col span="5">
								<span class="extra">付费方式：</span>现金
							</Col>
							<Col span="10">
								<span class="extra">付费金额：</span>￥100.00
							</Col>
						</Row>
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
					                <Option value="beijing">现金</Option>
					                <Option value="shanghai">支付宝</Option>
					                <Option value="shenzhen">微信</Option>
					            </Select>
							</Col>
							<Col span="10">
								<Input placeholder="付费金额"></Input>
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
								<Input placeholder="付费金额"></Input>
							</Col>
							<Col span="4" class="tr">
								<Button type="text" @click="deleteMoney">
									<i class="fa fa-trash icon-mr" aria-hidden="true"></i>删除
								</Button>
							</Col>
						</Row>
						<Row class="mt">
							<Col span="20">
								<Button type="dashed" long @click="addMoney">
									<i class="fa fa-plus icon-mr" aria-hidden="true"></i>添加一行
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
			            <Button type="primary" @click="goBack">确认换房</Button>
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
			info:0,
			money:0
		}
	},
	methods:{
		goBack(){
			history.go(-1);
		},
		addMoney(){
			this.money++;
		},
		deleteMoney(){
			this.money--;
		},
		turnUrl(url,query){
            this.$router.push(url)
        }
	}
}
</script>