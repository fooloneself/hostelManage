<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_member.php';?>
	<div class="frame">
		<form action="" class="form">
			<d-input>会员名称：</d-input>
			<d-input>手机号：</d-input>
			<d-input>微信号：</d-input>
			<d-input :type="'date'">生日：</d-input>
			<button class="btn">保存</button>
		</form>
	</div>
</div>
</div>
<?php include 'common/footer.php';?>