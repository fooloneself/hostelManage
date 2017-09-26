<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_house.php';?>
	<div class="frame">
		<form action="" class="form">
			<div class="mb20">
				<div class="label">时间周期：</div>
				<input type="date" class="input input_half">
				<input type="date" class="input input_half">
			</div>
			<div class="mb20">
				<div class="label">价格：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">浮动说明：</div>
				<textarea name="" id="" cols="30" rows="10" class="textarea"></textarea>
			</div>
			<button class="btn">取消</button>
			<button class="btn">提交后退出</button>
			<button class="btn">提交并新增</button>
		</form>
	</div>
</div>
<?php include 'common/footer.php';?>