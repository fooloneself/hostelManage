<template>
<div>
	<Menu mode="horizontal" @on-select="turnUrl" active-name="/admin" style="margin-top: -24px;height: 49px;line-height: 49px;z-index: 1">
        <MenuItem name="/admin">入住登记</MenuItem>
        <MenuItem name="/admin/checkstandOrder">预订登记</MenuItem>
    </Menu>
    <div class="mb"></div>
    <transition name="slideRight">
        <router-view></router-view>
    </transition>
</div>
</template>
<script>
export default{
	data (){
		return {
			
		}
	},
	methods:{
		turnUrl:function(name){
            this.$router.push(name);
        }
    }
}
</script>