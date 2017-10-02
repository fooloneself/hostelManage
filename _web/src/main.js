import Vue from 'vue'
import Vuex from 'vuex'
import App from './App'
import router from './router'
import VueResource from 'vue-resource'

import '../static/css/font-awesome.css'
import '../static/css/style.css'
import ResponseHandler from '../static/js/ResponseHandler'

Vue.config.productionTip = false
Vue.prototype.response = ResponseHandler
Vue.use(VueResource)
Vue.use(Vuex)
/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  render: h => h(App)
})
