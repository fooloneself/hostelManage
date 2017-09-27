<template id="d-modal">
	<div class="dialog" v-if="isshow">
		<div class="mask"></div>
		<div class="dialog_main">
			<div class="dialog_header">
				<slot name="header">提示</slot>
			</div>
			<div class="dialog_middle">
				<slot></slot>
			</div>
			<div class="dialog_footer">
				<button class="btn" @click="buttonCancel">取消</button>
				<button class="btn" @click="buttonConfirm">确定</button>
			</div>
		</div>
	</div>
</template>
<!-- Register Dialog -->
<script>
Vue.component('d-modal', {
	props:['isshow'],
	template: '#d-modal',
	methods:{
		buttonCancel: function(){
			this.$emit('iscancel');
		},
		buttonConfirm:function(){
			this.$emit('isconfirm');
		}
	}
});
</script>