<template id="dform">
	<form class="form">
		<template v-for="i in form.inputs">
		<div class="mb20" v-if="i.type=='select'">
			<div class="label">{{i.label}}</div>
			<select :name="i.items.name" class="select">
			<template v-for="j in i.items.option">
				<option :value="j.key">{{j.value}}</option>
			</template>
			</select>
		</div>
		<div class="mb20" v-else-if="i.type=='multi'">
			<div class="label">{{i.label}}</div>
			<template v-for="j in i.items">
			<input :type="j.type" :name="j.name" class="input input_half">
			</template>
			<div class="cls"></div>
		</div>
		<div class="mb20" v-else-if="i.type=='textarea'">
			<div class="label">{{i.label}}</div>
			<textarea :name="i.items.name" cols="30" rows="10" class="textarea"></textarea>
		</div>
		<div class="mb20" v-else>
			<div class="label">{{i.label}}</div>
			<input :type="i.type" :name="i.items.name" class="input">
		</div>
		</template>
		<template v-for="i in form.buttons">
			<button v-if="i=='save'" class="btn" @click="doSave">保存</button>
			<button v-else-if="i=='saveQuit'" class="btn" @click="doSaveQuit">保存后退出</button>
			<button v-else-if="i=='saveNew'" class="btn" @click="doSaveNew">保存并新增</button>
			<button v-else class="btn" @click="doCancel">取消</button>
			<slot name="button"></slot>
		</template>
	</form>
</template>
<!-- Register Input -->
<script>
Vue.component('dform', {
	props:['form'],
	template: '#dform',
	methods:{
		doSave:function(){
			this.$emit('save');
		},
		doSaveQuit:function(){
			this.$emit('saveQuit');
		},
		doSaveNew:function(){
			this.$emit('saveNew');
		},
		doCancel:function(){
			this.$emit('cancel');
		}
	}
});
</script>