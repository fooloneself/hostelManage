<template id="d-input">
	<div class="mb20" v-if="dType=='select'">
		<div class="label"><slot></slot></div>
		<select :name="name" class="select">
		<template v-for="item in items">
			<option :value="item.key">{{item.value}}</option>
		</template>
		</select>
	</div>
	<div class="mb20" v-else-if="dType=='textarea'">
		<div class="label"><slot></slot></div>
		<textarea :name="name" cols="30" rows="10" class="textarea"></textarea>
	</div>
	<div class="mb20" v-else-if="dType=='multi'">
		<div class="label"><slot></slot></div>
		<template v-for="item in items">
			<input :type="item.type" :name="item.name" class="input input_half">
		</template>
		<div class="cls"></div>
	</div>
	<div class="mb20" v-else>
		<div class="label"><slot></slot></div>
		<input :type="dType" :name="name" class="input">
	</div>
</template>
<!-- Register Pagination -->
<script>
Vue.component('d-input', {
	props:['type','name','items'],
	template: '#d-input',
	computed:{
		dType:function(){
			if(!this.type) return 'text';
			else return this.type;
		}
	},
});
</script>