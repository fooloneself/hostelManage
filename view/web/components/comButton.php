<template id="d-group-button">
	<div class="mb20">
		<template v-for="i in items">
		<a :href="i.url" class="btn">
			{{i.name}}
		</a>
		</template>
	</div>
</template>
<!-- Register GroupButton -->
<script>
Vue.component('d-group-button', {
	props:['items'],
	template: '#d-group-button'
});
</script>