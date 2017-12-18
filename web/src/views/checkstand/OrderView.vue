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
					<FormItem label="预订人信息">
						<Row :gutter="8">
							<Col span="24">
								<span class="extra">客人来源：</span>{{order.channel_name}}
							</Col>
						</Row>
						<Table size="small" :columns="member.columns" :data="member.data" stripe></Table>
			        </FormItem>
					<FormItem label="预订房信息">
						<Table size="small" :columns="room.columns" :data="room.data" stripe></Table>
			        </FormItem>
					<FormItem label="收费信息">
						<Table size="small" :columns="cost.columns" :data="cost.data" stripe></Table>
			        </FormItem>
					<FormItem label="备注信息">备注信息</FormItem>
					<FormItem>
			            <Button type="error">删除订单</Button>
			            <Button type="primary" class="icon-ml" @click="turnUrl('/admin/checkstandOrderEdit/'+$route.params.id)">修改订单</Button>
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
			member: {
				columns: [
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
	            data: [{name:'李波美',phone:'13800138000',rank:'非会员'}]
	        },
	        room: {
				columns: [
	                {
	                    title: '序号',
	                    width: 60,
	                    type: 'index'
	                },
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
	                    title: '总价',
	                    key: 'price'
	                }
	            ],
	            data: [
	            	{type:'豪华大床房',number:'201',price:'￥100.00',date:'2017/11/25 - 2017/12/01'},
	            	{type:'豪华大床房',number:'201',price:'￥100.00',date:'2017/11/25 - 2017/12/01'},
	            	{type:'豪华大床房',number:'201',price:'￥100.00',date:'2017/11/25 - 2017/12/01'},
	            	{type:'豪华大床房',number:'201',price:'￥100.00',date:'2017/11/25 - 2017/12/01'}
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
        }
	}
}
</script>