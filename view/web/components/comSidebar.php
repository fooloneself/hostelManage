<template id="dsidebar">
	<div class="sidebar">
		<dl class="menu">
		<template v-for="i in sidebar">
			<dt v-if="i.url==''">{{i.name}}</dt>
			<dd v-else :href="i.url">{{i.name}}</dd>
		</template>
		</dl>
	</div>
</template>
<!-- Register Sidebar -->
<script>
Vue.component('dsidebar', {
	props:['sidebar'],
	template: '#dsidebar'
});
</script>