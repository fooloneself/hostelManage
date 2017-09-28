<template id="d-modal">
	<div class="modal" v-if="modal.visible">
		<div class="mask"></div>
		<div class="modal_main">
			<div class="modal_header">
				提示
				<a href="javascript:;" @click="buttonCancel" class="fr">
					<i class="fa fa-times" aria-hidden="true"></i>
				</a>
			</div>
			<div class="modal_middle">
				{{modal.message}}
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
	props:['modal'],
	template: '#d-modal',
	methods:{
		buttonCancel: function(){
			this.$emit('cancel');
		},
		buttonConfirm:function(){
			this.$emit('confirm');
		}
	}
});
</script>