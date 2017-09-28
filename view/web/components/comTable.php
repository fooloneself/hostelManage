<template id="d-table">
<div>
	<div class="mb20" v-if="table.buttons">
		<template v-for="i in table.buttons">
		<a v-if="i.url!='modal'" :href="i.url" class="btn">
			{{i.name}}
		</a>
		<a v-else href="javascript:;" @click="showModal" class="btn">{{i.name}}</a>
		</template>
	</div>
	<table class="table" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td v-for="i in table.thead" :width="i.width">{{i.name}}</td>
			</tr>
		</thead>
		<tbody>
			<tr v-for="i in table.items">
				<td v-for="j in i">{{j}}</td>
			</tr>
		</tbody>
	</table>
	<div class="pagination">
		<a href=""><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
		<a href=""><i class="fa fa-angle-left" aria-hidden="true"></i></a>
		<template v-for="i in page">
			<a href="" v-if="i!=1 && i!='…'">{{i}}</a>
			<span v-else>{{i}}</span>
		</template>
		<a href=""><i class="fa fa-angle-right" aria-hidden="true"></i></a>
		<a href=""><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
	</div>
</div>
</template>
<!-- Register Table -->
<script>
Vue.component('d-table', {
	props:['table'],
	template: '#d-table',
	computed:{
		page:function(){
			if(this.table.pages>=6){
				return [1,2,3,4,5,6,'…',this.table.pages];
			} else {
				return this.table.pages;
			}
		}
	},
	methods:{
		showModal:function(){
			this.$emit('modal');
		}
	}
});
</script>