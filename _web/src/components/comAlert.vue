<template>
	<div class="modal" v-if="alert.visible">
		<div class="mask"></div>
		<div class="modal_main">
			<div class="modal_header">
				提示
				<a href="javascript:;" @click="buttonCancel" class="fr">
					<i class="fa fa-times" aria-hidden="true"></i>
				</a>
			</div>
			<div class="modal_middle">
				{{alert.message}}
			</div>
			<div class="modal_footer">
				<button class="btn" @click="buttonCancel">取消</button>
				<button class="btn" @click="buttonConfirm">确定</button>
			</div>
		</div>
	</div>
</template>
<script>
export default {
  name: 'dalert',
  props: ['alert'],
  methods: {
    buttonCancel: function () {
      this.alert.visible = false
      this.$emit('confirm', false)
    },
    buttonConfirm: function () {
      this.alert.visible = false
      this.$emit('confirm', true)
    }
  }
}
</script>