import Vue from 'vue'
import App from './App'
import router from './router'

import '../static/css/font-awesome.css'
import '../static/css/style.css'

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  render: h => h(App)
})
