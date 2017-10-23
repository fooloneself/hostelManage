<style scoped>
.pick-area{
	position: absolute;
	top: 60px;
	right: 0;
	left: 0;
	bottom: 0;
	background: #FFF;
}
.date-pick{
	background: url('../images/bj.png');
	padding-top: 10px;
	padding-bottom: 10px;
    min-width: 1208px;
    font-weight: bolder;
    .current{
    	color: #000;
    }
}
.room-pick{
	cursor: pointer;
	height: 100px;
	.room{
		position: relative;
		height: 84px;
		background: #FFF;
		border-radius: 5px;
		border: 1px solid #dddee1;
		&:hover{
			background: #dddee1;
		}
		.number{
			width: 100%;
			font-size: 28px;
			position: absolute;
			bottom: 8px;
			font-weight: bolder;
			text-align: center;
		}
		.type{
			width: 100%;
			text-align: center;
			line-height: 0;
			text-align: center;
			font-size: 14px;
			font-weight: normal;
			position: absolute;
			top: 24px;
		}
		&.room-in{
			background: #49D0B5;
			color: #FFFFFF;
			border: none;
		}
		&.room-order{
			background: #5688D2;
			color: #FFFFFF;
			border: none;
		}
		&.room-clock{
			background: #FD9A59;
			color: #FFFFFF;
			border: none;
		}
		&.room-dirty{
			background: #CCCCCC;
			color: #FFFFFF;
			border: none;
		}
		&.room-lock{
			background: #EEEEEE;
			color: #FFFFFF;
			border: none;
		}
	}
}
</style>
<template>
<div class="pick-area">
	<Spin size="large" fix v-if="spinShow"></Spin>
	<Row class="date-pick">
		<div class="container-body mlr">
			<Col span="2" class="tc">
				<Icon type="calendar" size="34"></Icon>
			</Col>
			<Col span="2" class="current tc">
				09-05 星期一<br/>剩余15间
			</Col>
			<Col v-for="week in weekday" span="2" class="tc">
				09-05 星期{{week}}<br/>剩余15间
			</Col>
		</div>
	</Row>
	<div class="mb"></div>
	<div class="container-body mlr">
		<Row>
			<Col span="12">
				<ButtonGroup size="small">
					<Button type="text">全部状态</Button>
					<Button type="text"><Icon type="record" style="color:#666666"></Icon><span class="icon-ml">空房</span></Button>
					<Button type="text"><Icon type="record" style="color:#49D0B5"></Icon><span class="icon-ml">入住</span></Button>
					<Button type="text"><Icon type="record" style="color:#5688D2"></Icon><span class="icon-ml">预订</span></Button>
					<Button type="text"><Icon type="record" style="color:#FD9A59"></Icon><span class="icon-ml">钟点</span></Button>
					<Button type="text"><Icon type="record" style="color:#CCCCCC"></Icon><span class="icon-ml">脏房</span></Button>
					<Button type="text"><Icon type="record" style="color:#EEEEEE"></Icon><span class="icon-ml">锁房</span></Button>
				</ButtonGroup>
			</Col>
			<Col span="12" class="tr">
				<ButtonGroup size="small">
					<Button type="text">全部房型</Button>
					<Button type="text">普通房</Button>
					<Button type="text">大床房</Button>
					<Button type="text">麻将房</Button>
					<Button type="text">套房</Button>
					<Button type="text">豪华房</Button>
				</ButtonGroup>
			</Col>
		</Row>
		<div class="mb"></div>
		<Row :gutter="16">
			<Col span="2" class="room-pick">
				<div class="room room-in" @click="turnUrl('checkstandView')">
					<div class="type">普通房</div>
					<div class="number">201</div>
				</div>
			</Col>
			<Col span="2" class="room-pick">
				<div class="room room-order" @click="turnUrl('checkstandView')">
					<div class="type">普通房</div>
					<div class="number">201</div>
				</div>
			</Col>
			<Col span="2" class="room-pick">
				<div class="room room-clock" @click="turnUrl('checkstandView')">
					<div class="type">普通房</div>
					<div class="number">201</div>
				</div>
			</Col>
			<Col span="2" class="room-pick">
				<div class="room room-dirty" @click="modalShow=true">
					<div class="type">普通房</div>
					<div class="number">201</div>
				</div>
			</Col>
			<Col span="2" class="room-pick">
				<div class="room room-lock">
					<div class="type">普通房</div>
					<div class="number">201</div>
				</div>
			</Col>
			<Col span="2" class="room-pick" v-for="i in rooms">
				<div class="room" @click="turnUrl('checkstandEdit')">
					<div class="type">普通房</div>
					<div class="number">201</div>
				</div>
			</Col>
		</Row>
	</div>
	<Modal v-model="modalShow" title="提示">
		<p>请确认是否将该房间从脏房置为空房？</p>
	</Modal>
</div>
</template>
<script>
export default{
	data (){
		return {
			modalShow: false,
			spinShow: false,
			rooms: 36,
			weekday: ['二','三','四','五','六','日','一','二','三','四']
		}
	},
	beforeCreate (){
	    this.host.post('resetMchPwd',{}).then(function(res){
	    });
	    this.host.post('resetMchPwd',{}).then(function(res){

	    });
	},
	methods:{
		turnUrl(url,query){
            this.$router.push(url)
        }
	}
}
</script>