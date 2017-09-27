<template id="d-modal">
	<div class="modal" v-if="isshow">
		<div class="mask"></div>
		<div class="modal_main">
			<div class="modal_header">
				<slot name="header">提示</slot>
				<a href="javascript:;" @click="buttonCancel" class="fr">
					<i class="fa fa-times" aria-hidden="true"></i>
				</a>
			</div>
			<div class="modal_middle">
				<slot></slot>
			</div>
			<div class="modal_footer">
				<button class="btn" @click="buttonCancel">取消</button>
				<button class="btn" @click="buttonConfirm">确定</button>
			</div>
		</div>
	</div>
</template>
<!-- Register Modal -->
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