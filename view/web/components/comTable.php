<template id="dtable">
<div>
	<div class="mb20" v-if="table.buttons">
		<template v-for="i in table.buttons">
		<a v-if="i.url!='alert'" :href="i.url" class="btn">
			{{i.name}}
		</a>
		<a v-else href="javascript:;" @click="doAlert" class="btn">{{i.name}}</a>
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
	<div class="pagination" v-if="table.buttons">
		<a href=""><i class="fa fa-angle-left" aria-hidden="true"></i></a>
		<template v-for="i in page.count">
			<a :href="page.url+i" v-if="i!=page.current && i!='…'">{{i}}</a>
			<span v-else>{{i}}</span>
		</template>
		<a href=""><i class="fa fa-angle-right" aria-hidden="true"></i></a>
	</div>
</div>
</template>
<!-- Register Table -->
<script>
Vue.component('dtable', {
	props:['table'],
	template: '#dtable',
	computed:{
		page:function(){
			var arr=[];
			var count=this.table.pages.count;
			var current=this.table.pages.current;
			if(count>=10){
				if(current-3<=3){
					arr = [1,2,3,4,5,6,7,'…',count];
				}
				else if (current>=count-5) {
					arr = [1,'…'];
					for(var i=count-8;i<=count;i++){
						arr.push(i)
					}
				}
				else {
					arr = [1,'…'];
					for(var i=current-3;i<=current+3;i++){
						arr.push(i);
					}
					arr.push('…',count);
				}
				return {count:arr,current:current,url:this.table.pages.preUrl};
			} else {
				return this.table.pages;
			}
		}
	},
	methods:{
		doAlert:function(){
			this.$emit('alert');
		}
	}
});
</script>