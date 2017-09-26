<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_member.php';?>
	<div class="frame">
		<div class="btn_group mb20">
			<button class="btn">新增</button>
		</div>
		<table class="table" cellpadding="0" cellspacing="1">
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
			<tbody v-for="page in pages">
				<tr>
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
						<a href="">编辑</a>
						<a href="">删除</a>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="pagination">
			<a href=""><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
			<a href=""><i class="fa fa-angle-left" aria-hidden="true"></i></a>
			<a href="">1</a>
			<span>2</span>
			<a href="">3</a>
			<a href="">4</a>
			<a href="">5</a>
			<a href="">6</a>
			<span>…</span>
			<a href="">16</a>
			<a href=""><i class="fa fa-angle-right" aria-hidden="true"></i></a>
			<a href=""><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
			<select name="" id="" v-model="pages">
				<option v-bind:value="12" selected="selected">每页12条</option>
				<option v-bind:value="24">每页24条</option>
				<option v-bind:value="36">每页36条</option>
				<option v-bind:value="48">每页48条</option>
			</select>
		</div>
	</div>
</div>
<script>
	new Vue({
		el:'#middle',
		data:{
			pages:12,
		}
	});
</script>
<?php include 'common/footer.php';?>