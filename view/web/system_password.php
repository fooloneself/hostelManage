<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_system.php';?>
	<div class="frame">
		<form action="" class="form">
			<d-input :type="'password'">旧密码：</d-input>
			<d-input :type="'password'">新密码：</d-input>
			<d-input :type="'password'">重复密码：</d-input>
			<button class="btn">保存</button>
		</form>
	</div>
</div>
</div>
<?php include 'components/comInput.php';?>
<script>
new Vue({
	el:'#middle'
});
</script>
<?php include 'common/footer.php';?>