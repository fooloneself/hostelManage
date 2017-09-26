<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_house.php';?>
	<div class="frame">
		<form action="" class="form">
			<div class="tab_menu mb20">
				<a href="house_type_week.php" class="current">周末浮动价格</a>
				<a href="house_type_date.php">特殊浮动价格</a>
			</div>
			<div class="mb20">
				<div class="label">周一价格：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">周二价格：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">周三价格：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">周四价格：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">周五价格：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">周六价格：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">周日价格：</div>
				<input type="text" class="input">
			</div>
			<a class="btn" href="house_type.php">取消</a>
			<button class="btn" type="submit">提交</button>
		</form>
	</div>
</div>
</div>
<?php include 'common/footer.php';?>