<template>
<div class="house">
		<div class="house_status fl">
			<a href="">全部</a>
			<a href="">入住</a>
			<a href="">预订</a>
			<a href="">钟点</a>
			<a href="">脏房</a>
			<a href="">空房</a>
			<a href="">锁房</a>
		</div>
		<div class="house_type fr">
			<a href="">全部</a>
			<a href="">普通房</a>
			<a href="">大床房</a>
			<a href="">麻将房</a>
			<a href="">套房</a>
			<a href="">豪华房</a>
		</div>
		<div class="cls"></div>
		<div class="house_list" v-for="house in houseNum" v-on:click="showOrder">
			<div class="type">普通房</div>
			<div class="number">201</div>
		</div>
		<div class="cls"></div>
	</div>
</template>
<script>
export default {
  data () {
    return {
      houseNum: 49
    }
  },
  methods: {
    showOrder () {
      this.$emit('showOrder', true)
    }
  }
}
</script>