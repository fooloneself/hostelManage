<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_system.php';?>
	<div class="frame">
		<div class="menu mb20">
			<a href="system_channel_edit.php" class="btn">新增</a>
		</div>
		<table class="table" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td width="6%">序号</td>
					<td width="20%">渠道名称</td>
					<td width="14%">设置佣金</td>
					<td>渠道说明</td>
					<td width="16%">操作</td>
				</tr>
			</thead>
			<tbody>
				<tr v-for="page in pages">
					<td>{{page}}</td>
					<td></td>
					<td></td>
					<td></td>
					<td>
						<a href="system_channel_edit.php">编辑</a>
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
			pages:10
		}
	})
</script>
<?php include 'common/footer.php';?>