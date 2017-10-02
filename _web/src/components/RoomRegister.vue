<template>
<div class="middle" id="middle">
    <nav-bar></nav-bar>
    <div class="container">
        <calender></calender>
        <house @showOrder='showOrder'></house>
    </div>
    <order v-bind:order='order' @showOrder='showOrder'></order>
</div>
</template>
<script>
import NavBar from '@/components/NavBar'
import Calender from '@/components/Calender'
import House from '@/components/House'
import Order from '@/components/Order'
export default{
  components: {
    'nav-bar': NavBar,
    'calender': Calender,
    'house': House,
    'order': Order
  },
  data () {
    return {
      order: false
    }
  },
  methods: {
    showOrder (show) {
      this.order = show
    }
  }
}
</script>