<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_member.php';?>
	<div class="frame">
		<form action="" class="form">
			<div class="mb20">
				<div class="label">会员名称：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">手机号：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">微信号：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">生日：</div>
				<input type="date" class="input">
			</div>
			<button class="btn">取消</button>
			<button class="btn">提交后退出</button>
			<button class="btn">提交并新增</button>
		</form>
	</div>
</div>
<?php include 'common/footer.php';?>