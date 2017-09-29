<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>考拉客房管理系统-登录</title>
	<script src="./static/js/vue.js"></script>
	<link rel="stylesheet" href="./static/css/font-awesome.min.css">
	<link rel="stylesheet" href="./static/css/style.css">
</head>
<body>
	<div class="body_bg"></div>
	<div class="login">
		<div class="login_logo fl">
			<div class="logo"><i class="fa fa-gg" aria-hidden="true"></i></div>
			<div class="title">考拉客房管理系统</div>
		</div>
		<div class="login_form fr">
			<p class="mb20">登录 / Login</p>
			<form action="view/web/checkstand.php" method="post">
				<input type="text" class="input" placeholder="请输入用户名">
				<input type="password" class="input" placeholder="请输入密码">
				<input type="text" class="input input_code" placeholder="请输入验证码">
				<div class="code">ASDAFG</div>
				<button class="btn btn_login">登录</button>
			</form>
		</div>
		<div class="cls"></div>
	</div>
</body>
</html>