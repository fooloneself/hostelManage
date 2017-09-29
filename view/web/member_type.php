<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_member.php';?>
	<div class="frame">
		<div class="btn_group mb20">
			<a href="member_type_edit.php" class="btn">新增</a>
		</div>
		<table class="table" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td width="6%">序号</td>
					<td>等级名称</td>
					<td width="30%">消费区间</td>
					<td width="30%">积分区间</td>
					<td width="20%">操作</td>
				</tr>
			</thead>
			<tbody>
				<tr v-for="page in pages">
					<td>{{page}}</td>
					<td></td>
					<td></td>
					<td></td>
					<td>
						<a href="member_type_edit.php">编辑</a>
						<a href="">删除</a>
					</td>
				</tr>
			</tbody>
		</table>
		<d-page :pages="pages"></d-page>
	</div>
</div>
</div>
<?php include 'components/comPage.php';?>
<script>
	new Vue({
		el:'#middle',
		data:{
			pages:10,
		}
	});
</script>
<?php include 'common/footer.php';?>