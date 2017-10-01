<style scoped>
.canlender a{
	float: left;
	width: 12.5%;
	padding: 10px 0;
	text-align: center;
	color: #16A085;
	font-size: 12px;
	&.pick{
		line-height: 34px;
		font-size: 16px;
		font-weight: bolder;
	}
	&.current,&:hover{
		color: #333333;
	}
}
.house{
	.house_status,.house_type{
		margin: 20px 5px;
		a{
			float: left;
			padding: 5px 10px;
			border: 1px solid #DDDDDD;
			margin-left: -1px;
			&:first-child{
				margin-left: 0px;
				border-top-left-radius: 5px;
				border-bottom-left-radius: 5px;
			}
			&:last-child{
				border-top-right-radius: 5px;
				border-bottom-right-radius: 5px;					
			}
		}
	}
	.house_list{
		cursor: pointer;
		width: 90px;
		height: 90px;
		border-radius: 5px;
		position: relative;
		border: 1px solid #DDDDDD;
		float: left;
		margin: 5px;
		color: #AAAAAA;
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
			top: 15px;
		}
	}
}
</style>
<template>
	<div class="checkstand">
		<div class="canlender">
			<a href="" class="pick">
				日历选择
			</a>
			<template v-for="week in weekday">
				<a href="">09-05 星期{{week}}<br/>剩余15间</a>
			</template>
			<div class="cls"></div>
		</div>
		<div class="house">
			<div class="house_status fl">
				<a href="">全部</a>
				<a href="">入住</a>
				<a href="">预订</a>
				<a href="">钟点</a>
				<a href="">脏房</a>
				<a href="">空房</a>
				<a href="">锁房</a>
			</div>
			<div class="house_type fr">
				<a href="">全部</a>
				<a href="">普通房</a>
				<a href="">大床房</a>
				<a href="">麻将房</a>
				<a href="">套房</a>
				<a href="">豪华房</a>
			</div>
			<div class="cls"></div>
			<div class="house_list" v-for="house in houseNum">
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
			houseNum:49,
			weekday:['一','二','三','四','五','六','日']
		}
	}
}
</script>