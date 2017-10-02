<style scoped>
.room-pick{
	cursor: pointer;
	width: 90px;
	height: 90px;
	border-radius: 5px;
	position: relative;
	border: 1px solid #DDDDDD;
	float: left;
	color: #AAAAAA;
	margin: 5px;
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
</style>
<template>
	<div>
		<Row class="mb">
			<Col span="3">
				<Row>
					<Col span="6" offset="6"><Icon type="calendar" size="34"></Icon></Col>
					<Col span="12">2017-10-10<br/>星期五</Col>
				</Row>
			</Col>
			<Col v-for="week in weekday" span="3" class="tc">
				09-05 星期{{week}}<br/>剩余15间
			</Col>
		</Row>
		<Row class="mb">
			<Col span="12">
				<ButtonGroup size="small">
					<Button>全部</Button>
					<Button><Icon type="record"></Icon><span class="icon-ml">入住</span></Button>
					<Button><Icon type="record"></Icon><span class="icon-ml">预订</span></Button>
					<Button><Icon type="record"></Icon><span class="icon-ml">钟点</span></Button>
					<Button><Icon type="record"></Icon><span class="icon-ml">脏房</span></Button>
					<Button><Icon type="record"></Icon><span class="icon-ml">空房</span></Button>
					<Button><Icon type="record"></Icon><span class="icon-ml">锁房</span></Button>
				</ButtonGroup>
			</Col>
			<Col span="12" class="tr">
				<ButtonGroup size="small">
					<Button>全部</Button>
					<Button>普通房</Button>
					<Button>大床房</Button>
					<Button>麻将房</Button>
					<Button>套房</Button>
					<Button>豪华房</Button>
				</ButtonGroup>
			</Col>
		</Row>
		<div>
			<div class="room-pick room-in">
				<div class="type">普通房</div>
				<div class="number">201</div>
			</div>
			<div class="room-pick room-order">
				<div class="type">普通房</div>
				<div class="number">201</div>
			</div>
			<div class="room-pick room-clock">
				<div class="type">普通房</div>
				<div class="number">201</div>
			</div>
			<div class="room-pick room-dirty">
				<div class="type">普通房</div>
				<div class="number">201</div>
			</div>
			<div class="room-pick room-lock">
				<div class="type">普通房</div>
				<div class="number">201</div>
			</div>
			<div class="room-pick" v-for="room in rooms">
				<div class="type">普通房</div>
				<div class="number">201</div>
			</div>
			<div class="cls"></div>
		</div>
	</div>
</template>
<script>
export default{
	data (){
		return {
			rooms:49,
			weekday:['一','二','三','四','五','六','日']
		}
	}
}
</script>