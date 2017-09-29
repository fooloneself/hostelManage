<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_member.php';?>
	<div class="frame">
		<form action="member_list.php" class="form">
			<d-input>会员名称：</d-input>
			<d-input>手机号：</d-input>
			<d-input>微信号：</d-input>
			<d-input :type="'date'">生日：</d-input>
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