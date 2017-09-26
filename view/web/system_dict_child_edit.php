<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_system.php';?>
	<div class="frame">
		<form action="system_dict_child.php" class="form">
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
			<a class="btn" href="javascript:history.go(-1)">取消</a>
			<button class="btn" type="submit">提交后退出</button>
			<button class="btn" type="reset">提交并新增</button>
		</form>
	</div>
</div>
</div>
<?php include 'common/footer.php';?>