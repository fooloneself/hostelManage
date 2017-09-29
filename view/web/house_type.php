<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_house.php';?>
	<div class="frame">
	<!-- 创建一个窗体table，并且表明需要用到窗体modal，触发方法为showModal -->
		<dtable :table="table" @alert="showAlert"></dtable>
	</div>
</div>
<!-- 窗体取消触发方法cancel，确认触发方法confirm -->
<dalert :alert="alert" @cancel="cancel" @confirm="confirm">
</dalert>
</div>
<?php
include 'components/comAlert.php';
include 'components/comTable.php';
?>
<script>
new Vue({
	el:'#middle',
	data:{
		table:{
			buttons:[
				{url:'house_type_edit.php',name:'新增'},
				{url:'house_type_edit.php',name:'编辑'},
				{url:'alert',name:'删除'},//将窗体授权给删除按钮
				{url:'house_type_week.php',name:'价格浮动'}
			],
			thead:[
				{width:'6%',name:'序号'},
				{width:'20%',name:'房间类型'},
				{width:'10%',name:'默认价格'},
				{width:'10%',name:'今日价格'},
				{width:'',name:'房型说明'}
			],
			items:[
				[1,'House Example','￥100.00','￥150.00','House Example'],
				[1,'House Example','￥100.00','￥150.00','House Example'],
				[1,'House Example','￥100.00','￥150.00','House Example'],
				[1,'House Example','￥100.00','￥150.00','House Example'],
				[1,'House Example','￥100.00','￥150.00','House Example'],
				[1,'House Example','￥100.00','￥150.00','House Example'],
				[1,'House Example','￥100.00','￥150.00','House Example'],
				[1,'House Example','￥100.00','￥150.00','House Example'],
				[1,'House Example','￥100.00','￥150.00','House Example'],
				[1,'House Example','￥100.00','￥150.00','House Example']
			],
			pages:{
				count:13,
				current:7,
				preUrl:'house_list.php?p='
			}
		},
		alert:{
			visible:false,
			message:''
		}
	},
	methods:{
		showAlert:function(){
			this.alert.visible=true;
			this.alert.message='猜到我是怎么实现的没？';
		},
		cancel:function(){
			this.alert.visible=false;
		},
		confirm:function(){
			this.alert.visible=false;
		}
	}
});
</script>
<?php include 'common/footer.php';?>