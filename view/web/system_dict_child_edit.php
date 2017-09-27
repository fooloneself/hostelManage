<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_system.php';?>
	<div class="frame">
		<form action="system_dict_child.php" class="form">
			<div class="label mb20">字典类型：性别</div>
			<d-input>数据名称：</d-input>
			<d-input :type="'number'">数据值：</d-input>
			<d-input :type="'number'">同级排序：</d-input>
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