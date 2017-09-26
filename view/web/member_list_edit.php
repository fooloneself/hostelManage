<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_member.php';?>
	<div class="frame">
		<form action="member_list.php" class="form">
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
			<a class="btn" href="javascript:history.go(-1)">取消</a>
			<button class="btn" type="submit">提交后退出</button>
			<button class="btn" type="reset">提交并新增</button>
		</form>
	</div>
</div>
</div>
<?php include 'common/footer.php';?>