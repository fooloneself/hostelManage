import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/components/Login'
import RoomRegister from '@/components/RoomRegister'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'login',
      component: Login
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    },
    {
      path: '/room-register',
      name: 'roomRegister',
      component: RoomRegister
    }
  ]
})
