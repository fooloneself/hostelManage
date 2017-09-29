<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_house.php';?>
	<div class="frame">
		<dform :form="form" @saveQuit="saveQuit" @saveNew="saveNew" @cancel="cancel"></dform>
	</div>
</div>
</div>
<?php
include 'components/comForm.php';
?>
<script>
new Vue({
	el:'#middle',
	data:{
		form:{
			inputs:[
				{label:'房屋类型：',type:'text',items:{name:''}},
				{label:'默认价格：',type:'text',items:{name:''}},
				{label:'钟点房开关：',type:'select',items:{
					name:'',
					option:[
						{key:0,value:'关'},
						{key:1,value:'开'}
					]
				}},
				{label:'钟点房时间：',type:'multi',items:[
					{type:'time',name:''},
					{type:'time',name:''},
				]},
				{label:'类型说明：',type:'textarea',items:{name:''}}
			],
			buttons:['saveQuit','saveNew','cancel']
		}

	},
	methods:{
		saveQuit:function(){},
		saveNew:function(){},
		cancel:function(){}
	}
});
</script>
<?php include 'common/footer.php';?>