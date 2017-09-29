<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_member.php';?>
	<div class="frame">
		<div class="btn_group mb20">
			<a href="member_list_edit.php" class="btn">新增</a>
		</div>
		<table class="table" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td width="6%">序号</td>
					<td width="12%">人员姓名</td>
					<td width="10%">手机号</td>
					<td width="12%">微信号</td>
					<td width="12%">生日</td>
					<td width="10%">会员等级</td>
					<td width="8%">消费金额</td>
					<td width="8%">积分</td>
					<td width="12%">注册时间</td>
					<td>操作</td>
				</tr>
			</thead>
			<tbody>
				<tr v-for="page in pages">
					<td>{{page}}</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>
						<a href="member_list_edit.php">编辑</a>
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