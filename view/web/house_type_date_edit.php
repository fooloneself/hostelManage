<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_house.php';?>
	<div class="frame">
		<form action="house_type_date.php" class="form">
			<d-input :type="'multi'" :items="week">时间周期：</d-input>
			<d-input>价格：</d-input>
			<d-input :type="'textarea'">浮动说明：</d-input>
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
	el:'#middle',
	data:{
		week:[
			{type:'date',name:''},
			{type:'date',name:''}
		]
	}
});
</script>
<?php include 'common/footer.php';?>