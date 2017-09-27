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
						<a href="javascript:;" @click="showModal">删除</a>
					</td>
				</tr>
			</tbody>
		</table>
		<d-page :pages="pages"></d-page>
	</div>
</div>
<d-modal @iscancel="cancel" @isconfirm="confirm" :isshow="show">
是否确否需要删除该条数据？
</d-modal>
</div>
<?php include 'components/comModal.php';?>
<?php include 'components/comPage.php';?>
<script>
new Vue({
	el:'#middle',
	data:{
		pages:10,
		show:false
	},
	methods:{
		showModal:function(){
			this.show=true;
		},
		cancel:function(){
			this.show=false;
		},
		confirm:function(){
			this.show=false;			
		}
	}
});
</script>
<?php include 'common/footer.php';?>