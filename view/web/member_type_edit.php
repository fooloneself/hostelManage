<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_member.php';?>
	<div class="frame">
		<form action="member_type.php" class="form">
			<d-input>等级名称：</d-input>
			<d-input :type="'multi'" :items="items1">消费区间：</d-input>
			<d-input :type="'multi'" :items="items2">积分区间：</d-input>
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
		items1:[
			{type:'text',name:''},
			{type:'text',name:''}
		],
		items2:[
			{type:'text',name:''},
			{type:'text',name:''}
		]
	}
});
</script>
<?php include 'common/footer.php';?>