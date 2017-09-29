<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_system.php';?>
	<div class="frame">
		<form action="" class="form">
			<d-input>账户名称：</d-input>
			<d-input>真实姓名：</d-input>
			<d-input :type="'password'">账户密码：</d-input>
			<d-input :type="'date'">有效期限：</d-input>
			<d-input :type="'file'">上传头像：</d-input>
			<d-input :type="'select'" :items="options">性别：</d-input>
			<d-input>联系方式：</d-input>
			<d-input :type="'date'">出生日期：</d-input>
			<button class="btn">保存</button>
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
			{type:'0',name:'女'},
			{key:'1',name:'男'}
		]
	}
});
</script>
<?php include 'common/footer.php';?>