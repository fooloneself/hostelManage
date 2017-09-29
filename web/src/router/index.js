import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/components/Login'
import RoomRegister from '@/components/RoomRegister'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      component: Login
    },
    {
      path: '/login',
      component: Login
    },
    {
      path: '/room-register',
      component: RoomRegister
    }
  ]
})
