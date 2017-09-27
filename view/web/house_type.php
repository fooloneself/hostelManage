<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_house.php';?>
	<div class="frame">
		<div class="btn_group mb20">
			<a href="house_type_edit.php" class="btn">新增</a>
		</div>
		<table class="table" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td width="6%">序号</td>
					<td width="20%">房间类型</td>
					<td width="10%">默认价格</td>
					<td width="10%">今日价格</td>
					<td>房型说明</td>
					<td width="20%">操作</td>
				</tr>
			</thead>
			<tbody>
				<tr v-for="page in pages">
					<td>{{page}}</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>
						<a href="house_type_week.php">价格浮动</a>
						<a href="house_type_edit.php">编辑</a>
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
				<option v-bind:value="10">每页10条</option>
				<option v-bind:value="20">每页20条</option>
				<option v-bind:value="30">每页30条</option>
				<option v-bind:value="40">每页40条</option>
			</select>
		</div>
	</div>
</div>
</div>
<script>
new Vue({
	el:'#middle',
	data:{
		pages:10,
	}
});
</script>
<?php include 'common/footer.php';?>