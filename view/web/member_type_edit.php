<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_member.php';?>
	<div class="frame">
		<form action="" class="form">
			<div class="mb20">
				<div class="label">等级名称：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">消费区间：</div>
				<input type="text" class="input input_half">
				<input type="text" class="input input_half">
			</div>
			<div class="mb20">
				<div class="label">积分区间：</div>
				<input type="text" class="input input_half">
				<input type="text" class="input input_half">
			</div>
			<button class="btn">取消</button>
			<button class="btn">提交后退出</button>
			<button class="btn">提交并新增</button>
		</form>
	</div>
</div>
<?php include 'common/footer.php';?>