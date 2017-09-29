<template id="dpage">
	<div class="pagination">
		<a href=""><i class="fa fa-angle-left" aria-hidden="true"></i></a>
		<template v-for="i in pages.count">
			<a :href="pages.url+i" v-if="i!=pages.current && i!='…'">{{i}}</a>
			<span v-else>{{i}}</span>
		</template>
		<a href=""><i class="fa fa-angle-right" aria-hidden="true"></i></a>
	</div>
</template>
<!-- Register Pagination -->
<script>
Vue.component('dpage', {
	props:['page'],
	template: '#dpage',
	computed:{
		pages:function(){
			var arr=[];
			var count=this.page.count;
			var current=this.page.current;
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
				return {count:arr,current:current,url:this.page.preUrl};
			} else {
				return this.page;
			}
		}
	}
});
</script>