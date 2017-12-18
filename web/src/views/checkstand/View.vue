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
					<FormItem label="订单总价："><span>￥{{order.amount}}</span></FormItem>
					<FormItem label="优惠活动：">
						<p>九折优惠</p>
					</FormItem>
					<FormItem label="应收金额："><span class="strong">￥{{order.amount_payable}}</span></FormItem>
					<FormItem label="已收金额："><span class="strong">￥{{order.amount_paid}}</span></FormItem>
					<FormItem label="待收金额："><span class="strong">￥{{order.amount_deffer}}</span></FormItem>
			    </Form>
			</Col>
			<Col span="20">
				<Form label-position="top" style="margin-left: 24px;">
				    <FormItem label="下单人信息">
                        <Row :gutter="8">
                            <Col span="5">
                                <span class="extra">姓名：</span>{{order.guest_name}}
                            </Col>
                             <Col span="5">
                                <span class="extra">手机号：</span>{{order.mobile}}
                            </Col>
                            <Col span="5">
                                <span class="extra">会员等级：</span>{{order.rank_name}}
                            </Col>
                        </Row>
                    </FormItem>
					<FormItem label="入住人信息">
						<Row :gutter="8">
							<Col span="5">
								<span class="extra">客人来源：</span>{{order.channel_name}}
							</Col>
						</Row>
						<Table size="small" :columns="member.columns" :data="member.data" no-data-text="暂无人入住" stripe></Table>
			        </FormItem>
					<FormItem label="入住房信息">
						<Row :gutter="8">
							<Col span="5">
								<span class="extra">入住方式：</span>{{order.type}}
							</Col>
							<Col span="5">
					            <span class="extra">入住时长：</span>{{order.quantity}}
							</Col>
						</Row>
						<Table size="small" :columns="room.columns" :data="room.data" no-data-text="无房间记录" stripe></Table>
			        </FormItem>
					<FormItem label="付费情况">
						<Table size="small" :columns="cost.columns" :data="cost.data" no-data-text="暂无付费记录" stripe></Table>
			        </FormItem>
					<FormItem label="备注信息">{{order.mark}}</FormItem>
					<!-- 这是入住订单的按钮 -->
					<FormItem>
			            <Button type="error" @click="goBack">删除订单</Button>
			            <Button type="primary" class="icon-ml" @click="toChangeRoom">换房</Button>
			            <Button type="primary" class="icon-ml" @click="toCheckOut">退房</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandContinue')">续住</Button>
                        <Button type="ghost" @click="goBack" class="icon-ml">返回</Button>
			        </FormItem>
					<!-- 这是预订订单的按钮
					<FormItem>
			            <Button type="error" @click="goBack">删除订单</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandEdit')">办理入住</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandChange')">换房</Button>
                        <Button type="ghost" @click="goBack" class="icon-ml">返回</Button>
			        </FormItem> -->
					<!-- 这是钟点房订单的按钮
					<FormItem>
			            <Button type="error" @click="goBack">删除订单</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandEdit')">办理入住</Button>
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
	                    title: '姓名',
	                    key: 'person_name'
	                },
	                {
	                    title: '手机号',
	                    key: 'mobile'
	                },
	                {
                        title: '入住时间',
                        key: 'occupancy_date'
                    },
	                {
	                    title: '会员等级',
	                    key: 'rank_name'
	                }
	            ],
	            data: []
	        },
	        room: {
				columns: [
	                {
	                    title: '入住时间',
	                    key: 'date'
	                },
	                {
	                    title: '房型',
	                    key: 'type_name'
	                },
	                {
	                    title: '房号',
	                    key: 'number'
	                },
	                {
	                    title: '单价',
	                    key: 'amount'
	                }
	            ],
	            data: []
	        },
			cost: {
				columns: [
	                {
	                    title: '收费项',
	                    key: 'expense_name'
	                },
	                {
	                    title: '付费方式',
	                    key: 'channel_name'
	                },
	                {
	                    title: '付费金额',
	                    key: 'amount'
	                }
	            ],
	            data: []
	        },
	        order:{}
		}
	},
	mounted(){
        var that=this;
        this.host.post('merchantOrderInfo',{orderId:this.$route.params.orderId,roomId:this.$route.params.roomId}).then(function(res){
            if(res.isSuccess()){
                that.order=res.data().order;
                that.member.data=res.data().occupancyRecord;
                that.room.data=res.data().costRecord;
                that.cost.data=res.data().payRecord;
            }else{
                this.$Notice.info({
                    title:'提示',
                    desc:res.error()
                });
            }
        })
    },
	methods:{
		goBack(){
			history.go(-1);
		},
		turnUrl(url,query){
            this.$router.push(url)
        },
        toChangeRoom(){
            this.turnUrl('/admin/checkstandChange/'+this.$route.params.orderId+'/'+this.$route.params.roomId);
        },
        toCheckOut(){
            this.turnUrl('/admin/checkstandOut/'+this.$route.params.orderId+'/'+this.$route.params.roomId);
        }
	}
}
</script>