<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_house.php';?>
	<div class="frame">
		<form action="house_list.php" class="form">
			<d-input :type="'select'" :items="options">房屋类型：</d-input>
			<d-input>房间号：</d-input>
			<d-input :type="'select'" :items="locks">是否锁房：</d-input>
			<d-input :type="'textarea'">房间说明：</d-input>
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
		options:[
			{key:0,value:'普通房'},
			{key:1,value:'大床房'}
		],
		locks:[
			{key:0,value:'不锁'},
			{key:1,value:'锁房'}
		]
	}
});
</script>
<?php include 'common/footer.php';?>