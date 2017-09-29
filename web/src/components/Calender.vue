<template>
<div class="canlender">
		<a href="" class="pick">
			<i class="fa fa-calendar" aria-hidden="true"></i>日历选择
		</a>
		<template v-for="week in weekday">
			<a href="" class="current">09-05 星期{{week}}<br/>剩余15间</a>
		</template>
		<div class="cls"></div>
	</div>
</template>
<script>
export default {
  data () {
    return {
      weekday: ['一', '二', '三', '四', '五', '六', '日']
    }
  }
}
</script>