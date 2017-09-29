<template id="dbutton">
	<div class="mb20">
		<template v-for="i in button">
		<a :href="i.url" class="btn">
			{{i.name}}
		</a>
		</template>
	</div>
</template>
<!-- Register GroupButton -->
<script>
Vue.component('dbutton', {
	props:['button'],
	template: '#dbutton'
});
</script>