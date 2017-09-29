<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_house.php';?>
	<div class="frame">
		<table class="table" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td width="6%">序号</td>
					<td width="14%">入住/退房时间</td>
					<td width="8%">客人来源</td>
					<td width="8%">支付方式</td>
					<td width="10%">房屋类型</td>
					<td width="6%">房间号</td>
					<td width="10%">入住人</td>
					<td width="10%">联系方式</td>
					<td width="8%">费用共计</td>
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
						<a href="">查看</a>
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