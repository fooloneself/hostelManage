<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_system.php';?>
	<div class="frame">
		<form action="" class="form">
			<div class="mb20">
				<div class="label">账户名称：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">真实姓名：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">账户密码：</div>
				<input type="password" class="input">
			</div>
			<div class="mb20">
				<div class="label">有效期限：</div>
				<input type="date" class="input">
			</div>
			<div class="mb20">
				<div class="label">上传头像：</div>
				<input type="file" class="input">
			</div>
			<div class="mb20">
				<div class="label">性别：</div>
				<select name="" id="" class="select">
					<option value="">男</option>
					<option value="" selected="selected">女</option>
				</select>
			</div>
			<div class="mb20">
				<div class="label">联系方式：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">出生日期：</div>
				<input type="date" class="input">
			</div>
			<button class="btn">保存</button>
		</form>
	</div>
</div>
<?php include 'common/footer.php';?>