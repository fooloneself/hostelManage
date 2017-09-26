<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_system.php';?>
	<div class="frame">
		<form action="" class="form">
			<div class="mb20">
				<div class="label">字典类型：</div>
				<input type="text" class="input" readonly="readonly" value="性别">
			</div>
			<div class="mb20">
				<div class="label">数据名称：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">数据值：</div>
				<input type="number" class="input">
			</div>
			<div class="mb20">
				<div class="label">同级排序：</div>
				<input type="number" class="input">
			</div>
			<button class="btn">取消</button>
			<button class="btn">提交后退出</button>
			<button class="btn">提交并新增</button>
		</form>
	</div>
</div>
<?php include 'common/footer.php';?>