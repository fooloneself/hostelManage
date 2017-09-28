<template id="d-sidebar">
	<div class="sidebar">
		<dl class="menu">
		<template v-for="i in items">
			<dt v-if="i.url===''">{{i.name}}</dt>
			<dd v-else :href="i.url">{{i.name}}</dd>
		</template>
		</dl>
	</div>
</template>
<!-- Register Sidebar -->
<script>
Vue.component('d-sidebar', {
	props:['items'],
	template: '#d-sidebar'
});
</script>