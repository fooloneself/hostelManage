<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_system.php';?>
	<div class="frame">
		<form action="system_channel.php" class="form">
			<d-input>渠道名称：</d-input>
			<d-input>设置佣金：</d-input>
			<d-input :type="'textarea'">渠道说明：</d-input>
			<a class="btn" href="javascript:history.go(-1)">取消</a>
			<button class="btn" type="submit">提交后退出</button>
			<button class="btn" type="reset">提交并新增</button>
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