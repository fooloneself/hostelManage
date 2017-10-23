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
	<div class="container-body">
		<div class="mb"></div>
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
							<Col span="12">手机号：13800138000</Col>
							<Col span="12">姓名：D.Seeyou</Col>
							<Col span="12">手机号：13800138000</Col>
							<Col span="12">姓名：D.Seeyou</Col>
						</Row>			
			        </FormItem>
					<FormItem label="入住情况：">
						<Row :gutter="8">
							<Col span="6">
								订单来源：美团
							</Col>
							<Col span="6">
								订单方式：入住
							</Col>
							<Col span="12">
								订单时间：2017/09/10~2017/09/11
							</Col>
						</Row>
			        </FormItem>
					<FormItem label="费用情况：">
						<Row :gutter="8">
							<Col span="6">
								付费项：收取房费
							</Col>
							<Col span="6">
								付费方式：现金
							</Col>
							<Col span="12">
								金额：￥100
							</Col>
							<Col span="6">
								付费项：收取房费
							</Col>
							<Col span="6">
								付费方式：现金
							</Col>
							<Col span="12">
								金额：￥100
							</Col>
							<Col span="6">
								付费项：收取房费
							</Col>
							<Col span="6">
								付费方式：现金
							</Col>
							<Col span="12">
								金额：￥100
							</Col>
						</Row>
			        </FormItem>
					<FormItem label="备注："></FormItem>
					<!-- 这是入住订单的按钮 -->
					<FormItem>
			            <Button type="primary" @click="goBack">取消订单</Button>
			            <Button type="primary" @click="turnUrl('checkstandEdit')">修改订单</Button>
			            <Button type="primary" @click="turnUrl('checkstandChange')">换房</Button>
			            <Button type="primary" @click="turnUrl('checkstandOut')">退房</Button>
			            <Button type="primary" @click="goBack">一键续住</Button>
			        </FormItem>
					<!-- 这是预订订单的按钮 -->
					<FormItem>
			            <Button type="primary" @click="goBack">取消订单</Button>
			            <Button type="primary" @click="turnUrl('checkstandEdit')">办理入住</Button>
			        </FormItem>
					<!-- 这是终点房订单的按钮 -->
					<FormItem>
			            <Button type="primary" @click="goBack">取消订单</Button>
			            <Button type="primary" @click="turnUrl('checkstandEdit')">修改订单</Button>
			            <Button type="primary" @click="turnUrl('checkstandChange')">换房</Button>
			            <Button type="primary" @click="turnUrl('checkstandOut')">退房</Button>
			            <Button type="primary" @click="turnUrl('checkstandEdit')">办理入住</Button>
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