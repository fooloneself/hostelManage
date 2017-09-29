<?php include 'common/header.php';?>
<div class="container">
	<div class="canlender">
		<a href="" class="pick">
			<i class="fa fa-calendar mr5" aria-hidden="true"></i>日历选择
		</a>
		<template v-for="week in weekday">
			<a href="" class="current">09-05 星期{{week}}<br/>剩余15间</a>
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
		<div class="house_list" v-for="house in houseNum" v-on:click="showOrder">
			<div class="type">普通房</div>
			<div class="number">201</div>
		</div>
		<div class="cls"></div>
	</div>
</div>
<div class="dialog" id="editOrder" v-if="order">
	<div class="mask"></div>
	<div class="dialog_main">
		<div class="dialog_header">编辑订单</div>
		<div class="dialog_middle">
			<div class="money">
				订单金额：<span>￥168.00</span>
				已收金额：<span>￥100</span>
				需补房费：<span>￥68.00</span>
			</div>
			<div class="edit">
				<div class="info">入住人信息</div>
				<input type="text" class="input" placeholder="手机号" style="width: 100px">
				<input type="text" class="input" placeholder="姓名" style="width: 60px">
				<input type="text" class="input" placeholder="微信号" style="width: 130px">
				<a href="">删除</a>
				<div class="add_info"><a href="">添加入住人</a></div>
			</div>
			<div class="edit">
				<div class="info">房间情况</div>
				<select name="" id="" class="select" style="width: 120px;margin-right: 5px">
					<option value="">美团</option>
					<option value="">携程</option>
					<option value="">线下</option>
				</select><select name="" id="" class="select" style="width: 80px">
					<option value="">入住</option>
					<option value="">预订</option>
					<option value="">钟点</option>
				</select>
				<input type="date" class="input" placeholder="入住时间" style="width: 130px">
				<a href="">删除</a>
				<div class="add_info"><a href="">添加入住时间</a></div>
			</div>
			<div class="edit">
				<div class="info">收费情况</div>
				<select name="" id="" class="select" style="width: 120px;margin-right: 5px">
					<option value="">收取房费</option>
					<option value="">收取押金</option>
					<option value="">收取定金</option>
					<option value="">退还房费</option>
					<option value="">退还押金</option>
					<option value="">退还定金</option>
				</select><select name="" id="" class="select" style="width: 80px">
					<option value="">现金</option>
					<option value="">微信</option>
					<option value="">支付宝</option>
				</select>
				<input type="text" class="input" placeholder="￥" style="width: 130px">
				<a href="">删除</a>
				<div class="add_info"><a href="">添加收费</a></div>
			</div>
			<div class="edit mb20">
				<div class="info">备注</div>
				<textarea name="" id="" cols="50" rows="10" class="textarea" style="width: 370px"></textarea>
			</div>
		</div>
		<div class="dialog_footer">
			<button class="btn" v-on:click="hideOrder">取消</button>
			<button class="btn">确认</button>
		</div>
	</div>
</div>
</div>
<script>
new Vue({
	el: '#middle',
	data:{
		houseNum:49,
		order:false,
		weekday:['一','二','三','四','五','六','日'],
	},
	methods:{
		showOrder: function(){
			this.order=true;
		},
		hideOrder: function(){
			this.order=false;
		}
	}
});
</script>
<?php include 'common/footer.php';?>