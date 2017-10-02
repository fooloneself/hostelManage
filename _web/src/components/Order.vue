<template>
<div class="dialog" id="editOrder" v-show="order">
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
</template>
<script>
export default {
  props: ['order'],
  methods: {
    hideOrder () {
      this.$emit('showOrder', false)
    }
  }
}
</script>