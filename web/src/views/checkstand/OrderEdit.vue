<style scoped>
.order-info{
	background: #ECF0F1;
	border-radius: 5px;
	padding: 24px;
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
		<Col span="10">
			<Form label-position="top" style="margin-left: 24px;">
				<FormItem label="预订情况">
					<Row :gutter="8">
						<Col span="5">
							<Select placeholder="客人来源">
				                <Option value="0">来源一</Option>
				            </Select>
						</Col>
						<Col span="8"><Input placeholder="预订人姓名"></Input></Col>
						<Col span="8"><Input placeholder="手机号"></Input></Col>
					</Row>
					<Row :gutter="8" class="mt">
						<Col span="8">
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
							<DatePicker type="date" placeholder="选择日期"></DatePicker>
						</Col>
						<Col span="6">
							<Button type="text" @click="addOrder">
								<i class="fa fa-plus icon-mr" aria-hidden="true"></i>
								添加房间
							</Button>
						</Col>
					</Row>
		        </FormItem>
				<FormItem label="消费情况">
					<Row :gutter="8">
						<Col span="10">
							<Input placeholder="房间总价：￥100.00"></Input>
						</Col>
						<Col span="10">
							<Select placeholder="优惠活动">
				                <Option value="-1">请选择活动</Option>
				                <Option value="0">优惠活动二</Option>
				                <Option value="1">优惠活动三</Option>
				            </Select>
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
								<i class="fa fa-trash icon-mr" aria-hidden="true"></i>删除
							</Button>
						</Col>
					</Row>
					<Row class="mt">
						<Col span="20">
							<Button type="dashed" long @click="addMoney">
								<i class="fa fa-plus icon-mr" aria-hidden="true"></i>添加收费项
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
					<!-- 新添订单 -->
		            <Button type="primary">确认预订</Button>
					<!-- 修改订单 -->
		            <Button type="primary">确认修改</Button>
                    <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
		        </FormItem>
		    </Form>
		</Col>
		<Col span="10">
			<Table :columns="columns" :data="data" stripe></Table>
		</Col>
	</Row>
</div>
</template>

<script>
export default{
	data () {
		return {
			money:0,
			columns: [
                {
                    title: '序号',
                    width: 60,
                    type: 'index'
                },
                {
                    title: '房型',
                    key: 'type'
                },
                {
                    title: '房号',
                    width: 80,
                    key: 'number'
                },
                {
                    title: '单价',
                    width: 100,
                    key: 'price'
                },
                {
                    title: '预订时间',
                    key: 'date'
                },
                {
                    title: '操作',
                    key: 'action',
                    render: (h, params) => {
                        return h('div', [
                            h('Button', {
                                props: {
                                    type: 'text',
                                    size: 'small'
                                },
                                on: {
                                	click: ()=>{
                                		if(this.data.length>1)
                                			this.data.splice(1,1);
                                		else
                                			this.$Notice.info({
								                title:'提示',
								                desc:'预订订单不能没有房间！'
								            });
                                	}
                                }
                            }, '删除')
                        ]);
                    }
                }
            ],
            data: [
            	{type:'豪华大床房',number:'201',price:'￥100.00',date:'2017/11/25'},
            	{type:'豪华大床房',number:'201',price:'￥100.00',date:'2017/11/25'},
            	{type:'豪华大床房',number:'201',price:'￥100.00',date:'2017/11/25'},
            	{type:'豪华大床房',number:'201',price:'￥100.00',date:'2017/11/25'},
            	{type:'豪华大床房',number:'201',price:'￥100.00',date:'2017/11/25'},
            	{type:'豪华大床房',number:'201',price:'￥100.00',date:'2017/11/25'}
            ],
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
		addOrder(){
			this.data.push({type:'豪华大床房',number:'201',price:'￥100.00',date:'2017/11/25'});
		}
	}
}
</script>