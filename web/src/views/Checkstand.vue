<style scoped>
.date-pick{
	background: #EEEEEE;
	padding-top: 10px;
	padding-bottom: 10px;
}
.room-pick{
	cursor: pointer;
	width: 10%;
	min-width: 100px;
	height: 100px;
	float: left;
	.room{
		margin: 5px;
		border: 1px solid #DDDDDD;
		position: relative;
		color: #666666;
		height: 90px;
		border-radius: 5px;
		&:hover{
			background: #DDDDDD;
		}
		.number{
			width: 100%;
			font-size: 28px;
			position: absolute;
			bottom: 15px;
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
			top: 16px;
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
<div>
	<Row class="date-pick">
		<div class="container">
			<Col span="2" class="tc">
				<Icon type="calendar" size="34"></Icon>
			</Col>
			<Col v-for="week in weekday" span="2" class="tc">
				09-05 星期{{week}}<br/>剩余15间
			</Col>
		</div>
	</Row>
	<div class="mb"></div>
	<div class="container">
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
		<div style="margin: -5px;">
			<div class="room-pick">
				<div class="room room-in">
					<div class="type">普通房</div>
					<div class="number">201</div>
				</div>
			</div>
			<div class="room-pick">
				<div class="room room-order">
					<div class="type">普通房</div>
					<div class="number">201</div>
				</div>
			</div>
			<div class="room-pick">
				<div class="room room-clock">
					<div class="type">普通房</div>
					<div class="number">201</div>
				</div>
			</div>
			<div class="room-pick">
				<div class="room room-dirty">
					<div class="type">普通房</div>
					<div class="number">201</div>
				</div>
			</div>
			<div class="room-pick">
				<div class="room room-lock">
					<div class="type">普通房</div>
					<div class="number">201</div>
				</div>
			</div>
			<div class="room-pick" v-for="room in rooms">
				<div class="room">
					<div class="type">普通房</div>
					<div class="number">201</div>
				</div>
			</div>
			<div class="cls"></div>
		</div>
	</div>
</div>
</template>
<script>
export default{
	data (){
		return {
			rooms:36,
			weekday:['一','二','三','四','五','六','日','一','二','三','四']
		}
	}
}
</script>