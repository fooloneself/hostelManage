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
					<FormItem label="原房信息">
						<Row :gutter="8">
							<Col span="5">
								<span class="extra">入住方式：</span>全天房
							</Col>
							<Col span="5">
					            <span class="extra">入住时长：</span>2晚
							</Col>
						</Row>
						<Table size="small" :columns="room.columns" :data="room.data" stripe></Table>
			        </FormItem>
					<FormItem label="换房操作">
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
							<Col span="5">
								<Input placeholder="168.00">
									<span slot="prepend">订单总价</span>
								</Input>
							</Col>
							<Col span="5">
								<Select placeholder="优惠活动">
					                <Option value="-1">请选择活动</Option>
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
									<i class="fa fa-plus icon-mr" aria-hidden="true"></i>添加收费
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
			            <Button type="primary" @click="goBack">确认换房</Button>
                        <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
			        </FormItem>
					<FormItem label="入住人信息">
						<Row :gutter="8">
							<Col span="5">
								<span class="extra">客人来源：</span>美团
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
	                    title: '人员类型',
	                    key: 'type'
	                },
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
	                }
	            ],
	            data: [
	            	{type:'预订人',name:'李波',phone:'13800138000',rank:'非会员'},
	            	{type:'入住人',name:'李波',phone:'13800138000',rank:'非会员'},
	            	{type:'入住人',name:'李波媳妇',phone:'13800138000',rank:'非会员'},
	            ]
	        },
	        room: {
				columns: [
	                {
	                    title: '预订时间',
	                    key: 'date'
	                },
	                {
	                    title: '房型',
	                    key: 'type'
	                },
	                {
	                    title: '房号',
	                    key: 'number'
	                },
	                {
	                    title: '单价',
	                    key: 'price'
	                }
	            ],
	            data: [
	            	{type:'豪华大床房',number:'201',price:'￥100.00',date:'2017/11/25'}
	            ]
	        },
			cost: {
				columns: [
	                {
	                    title: '收费项',
	                    key: 'classic'
	                },
	                {
	                    title: '付费方式',
	                    key: 'type'
	                },
	                {
	                    title: '付费金额',
	                    key: 'price'
	                }
	            ],
	            data: [
	            	{classic:'房费',type:'现金',price:'￥100.00'},
	            	{classic:'房费',type:'现金',price:'￥100.00'},
	            	{classic:'房费',type:'现金',price:'￥100.00'}
	            ]
	        }
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