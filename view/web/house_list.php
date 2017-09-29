<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_house.php';?>
	<div class="frame">
		<dtable :table="table" @alert="showAlert"></dtable>
	</div>
</div>
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
				{url:'house_list_edit.php',name:'新增'},
				{url:'house_list_edit.php',name:'编辑'},
				{url:'alert',name:'删除'},//将窗体授权给删除按钮
				{url:'',name:'锁房'}
			],
			thead:[
				{width:'6%',name:'序号'},
				{width:'20%',name:'房间类型'},
				{width:'10%',name:'默认价格'},
				{width:'10%',name:'房间号'},
				{width:'',name:'房屋状态'}
			],
			items:[
				[1,'House Example','￥100.00','201','House Example'],
				[1,'House Example','￥100.00','201','House Example'],
				[1,'House Example','￥100.00','201','House Example'],
				[1,'House Example','￥100.00','201','House Example'],
				[1,'House Example','￥100.00','201','House Example'],
				[1,'House Example','￥100.00','201','House Example'],
				[1,'House Example','￥100.00','201','House Example'],
				[1,'House Example','￥100.00','201','House Example'],
				[1,'House Example','￥100.00','201','House Example'],
				[1,'House Example','￥100.00','201','House Example']
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