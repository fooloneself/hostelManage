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
<div class="layout-body">
	<div class="container-body">
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
								<span class="extra">客人来源：</span>美团
							</Col>
							<Col span="8"><span class="extra">预订人姓名：</span>D.Seeyou</Col>
							<Col span="8"><span class="extra">手机号：</span>13800138000</Col>
						</Row>
			        </FormItem>
					<FormItem label="消费情况">
						<Row :gutter="8">
							<Col span="10">
					            <span class="extra">房间总价：</span>￥168.00
							</Col>
							<Col span="10">
					            <span class="extra">优惠活动：</span>优惠活动二
							</Col>
						</Row>
						<Row :gutter="8" v-for="i in 3">
							<Col span="5">
								<span class="extra">收费项：</span>收取房费
							</Col>
							<Col span="5">
								<span class="extra">付费方式：</span>现金
							</Col>
							<Col span="10">
								<span class="extra">付费金额：</span>￥100
							</Col>
						</Row>
			        </FormItem>
					<FormItem label="备注信息">备注信息</FormItem>
					<FormItem>
			            <Button type="error" @click="goBack">删除订单</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandOrderEdit')">修改订单</Button>
                        <Button type="ghost" @click="goBack" class="icon-ml">返回</Button>
			        </FormItem>
			    </Form>
			</Col>
			<Col span="10">
				<Table :columns="columns" :data="data" stripe></Table>
			</Col>
		</Row>
	</div>
</div>
</template>

<script>
export default{
	data () {
		return {
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
		turnUrl(url,query){
            this.$router.push(url)
        }
	}
}
</script>