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
			<Col span="14">
				<Form label-position="top" style="margin-left: 24px;">
					<FormItem label="入住情况">
						<Row :gutter="8">
							<Col span="5"><span class="extra">房型：</span>豪华大床房</Col>
							<Col span="5"><span class="extra">房号：</span>201</Col>
							<Col span="10"><span class="extra">入住时间：</span>2017/11/25 (￥100.00)</Col>
						</Row>
						<Row :gutter="8">
							<Col span="5">
								<span class="extra">客人来源：</span>美团
							</Col>
							<Col span="5">
								<span class="extra">入住方式：</span>全日房
							</Col>
							<Col span="5">
					            <span class="extra">入住时长：</span>2晚
							</Col>
						</Row>
						<Row :gutter="8">
							<Col span="10"><span class="extra">姓名：</span>D.Seeyou</Col>
							<Col span="10"><span class="extra">手机号：</span>13800138000</Col>
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
					<!-- 这是入住订单的按钮 -->
					<FormItem>
			            <Button type="error" @click="goBack">删除订单</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandEdit')">修改订单</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandChange')">换房</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandOut')">退房</Button>
			            <Button type="primary" class="icon-ml" @click="goBack">续住</Button>
                        <Button type="ghost" @click="goBack" class="icon-ml">返回</Button>
			        </FormItem>
					<!-- 这是预订订单的按钮 -->
					<FormItem>
			            <Button type="error" @click="goBack">删除订单</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandEdit')">办理入住</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandChange')">换房</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandEdit')">修改订单</Button>
                        <Button type="ghost" @click="goBack" class="icon-ml">返回</Button>
			        </FormItem>
					<!-- 这是钟点房订单的按钮 -->
					<FormItem>
			            <Button type="error" @click="goBack">删除订单</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandEdit')">办理入住</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandEdit')">修改订单</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandChange')">换房</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandOut')">退房</Button>
                        <Button type="ghost" @click="goBack" class="icon-ml">返回</Button>
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
		turnUrl(url,query){
            this.$router.push(url)
        }
	}
}
</script>