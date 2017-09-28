<template id="d-page">
	<div class="pagination">
		<a href=""><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
		<a href=""><i class="fa fa-angle-left" aria-hidden="true"></i></a>
		<template v-for="i in items">
			<a href="" v-if="i!=1 && i!='…'">{{i}}</a>
			<span v-else>{{i}}</span>
		</template>
		<a href=""><i class="fa fa-angle-right" aria-hidden="true"></i></a>
		<a href=""><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
	</div>
</template>
<!-- Register Pagination -->
<script>
Vue.component('d-page', {
	props:['pages'],
	template: '#d-page',
	computed:{
		items:function(){
			if(this.pages>=6){
				return [1,2,3,4,5,6,'…',this.pages];
			} else {
				return this.pages;
			}
		}
	}
});
</script>