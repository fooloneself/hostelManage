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
					<FormItem label="入住人信息">
						<Row :gutter="8">
							<Col span="5">
								<span class="extra">客人来源：</span>美团
							</Col>
						</Row>
						<Table size="small" :columns="member.columns" :data="member.data" stripe></Table>
			        </FormItem>
					<FormItem label="入住房信息">
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
					<FormItem label="收费情况">
						<Table size="small" :columns="cost.columns" :data="cost.data" stripe></Table>
			        </FormItem>
					<FormItem label="备注信息">备注信息</FormItem>
					<!-- 这是入住订单的按钮 -->
					<FormItem>
			            <Button type="error" @click="goBack">删除订单</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandEdit/'+$route.params.id)">修改订单</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandChange')">换房</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandOut')">退房</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandContinue')">续住</Button>
                        <Button type="ghost" @click="goBack" class="icon-ml">返回</Button>
			        </FormItem>
					<!-- 这是预订订单的按钮
					<FormItem>
			            <Button type="error" @click="goBack">删除订单</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandEdit')">办理入住</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandEdit')">修改订单</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandChange')">换房</Button>
                        <Button type="ghost" @click="goBack" class="icon-ml">返回</Button>
			        </FormItem> -->
					<!-- 这是钟点房订单的按钮
					<FormItem>
			            <Button type="error" @click="goBack">删除订单</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandEdit')">办理入住</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandEdit')">修改订单</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandChange')">换房</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandOut')">退房</Button>
                        <Button type="ghost" @click="goBack" class="icon-ml">返回</Button>
			        </FormItem> -->
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
	                },
	                {
	                    title: '余额',
	                    key: 'price'
	                }
	            ],
	            data: [
	            	{type:'预订人',name:'李波',phone:'13800138000',rank:'非会员',price:'￥0.00'},
	            	{type:'入住人',name:'李波',phone:'13800138000',rank:'非会员',price:'￥0.00'},
	            	{type:'入住人',name:'李波媳妇',phone:'13800138000',rank:'非会员',price:'￥0.00'},
	            ]
	        },
	        room: {
				columns: [
	                {
	                    title: '入住时间',
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
	            	{classic:'房费',type:'余额',price:'￥100.00'},
	            	{classic:'房费',type:'余额',price:'￥100.00'},
	            	{classic:'房费',type:'余额',price:'￥100.00'}
	            ]
	        }
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