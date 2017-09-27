<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_house.php';?>
	<div class="frame">
		<form action="house_type.php" class="form">
			<d-input :name="'type'">房屋类型：</d-input>
			<d-input :name="'price'">默认价格：</d-input>
			<d-input :type="'select'" :items="options">钟点房开关：</d-input>
			<d-input :type="'multi'" :items="inputs">钟点房时间：</d-input>
			<d-input :type="'textarea'">类型说明：</d-input>
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
			{key:0,value:'关'},
			{key:1,value:'开'}
		],
		inputs:[
			{type:'time',name:''},
			{type:'time',name:''}
		]
	}
});
</script>
<?php include 'common/footer.php';?>