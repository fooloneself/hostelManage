<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_house.php';?>
	<div class="frame">
		<div class="menu mb20">
			<a href="house_list_edit.php" class="btn">新增</a>
		</div>
		<table class="table" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td width="6%">序号</td>
					<td width="20%">房间类型</td>
					<td width="10%">默认价格</td>
					<td width="10%">房间号</td>
					<td>房屋状态</td>
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
						<a href="">锁房</a>
						<a href="house_list_edit.php">编辑</a>
						<a href="javascript:;" v-on:click="showOrder">删除</a>
					</td>
				</tr>
			</tbody>
		</table>
		<d-page :pages="pages"></d-page>
	</div>
</div>
<div class="dialog" v-if="order">
	<div class="mask"></div>
	<div class="dialog_main">
		<div class="dialog_header">提示</div>
		<div class="dialog_middle">
		请问是否需要删除这一条数据？
		</div>
		<div class="dialog_footer">
			<button class="btn" v-on:click="hideOrder">取消</button>
			<button class="btn">确认</button>
		</div>
	</div>
</div>
</div>
<?php include 'components/comPage.php';?>
<script>
	new Vue({
		el:'#middle',
		data:{
			pages:10,
			order:false
		},
		methods:{
			showOrder: function(){
				this.order=true;
			},
			hideOrder: function(){
				this.order=false;
			}
		}
	});
</script>
<?php include 'common/footer.php';?>