<template>
<div class="body_bg">
    <div class="login">
        <div class="login_logo fl">
            <div class="logo"><i class="fa fa-gg" aria-hidden="true"></i></div>
            <div class="title">考拉客房管理系统</div>
        </div>
        <div class="login_form fr">
            <p class="mb20">登录 / Login</p>
            <form @submit.prevent="login">
                <input type="text" class="input" v-model="userName" placeholder="请输入用户名">
                <input type="password" class="input" v-model="password" placeholder="请输入密码">
                <input type="text" class="input input_code" v-model="code" placeholder="请输入验证码">
                <div @click="changeCode" class="code" >{{viewCode}}</div>
                <button type="submit" class="btn btn_login">登录</button>
            </form>
        </div>
        <div class="cls"></div>
    </div>
    <dalert :alert="alert"></dalert>
</div>
</template>
<script>
import Dalert from '@/components/comAlert'
export default {
  name: 'login',
  data () {
    return {
      viewCode: '',
      userName: '',
      password: '',
      code: '',
      alert: {
        message: '',
        code: 0,
        visible: false
      }
    }
  },
  components: {
    'dalert': Dalert
  },
  mounted () {
    this.$on('confirm', function (confirm) {
      console.log('success')
      this.alert.visible = false
    })
  },
  created () {
    this.changeCode()
  },
  methods: {
    changeCode () {
      this.$http.post('/interface/site/capture').then(function (data) {
        this.viewCode = this.response(data).data()
      })
    },
    login () {
      this.$http.post('/interface/admin/login', {userName: this.userName, password: this.password, code: this.code}).then(function (data) {
        let res = this.response(data)
        if (res.isSuccess()) {
          this.$router.push({name: 'roomRegister'})
        } else {
          this.alert.message = res.error()
          this.alert.code = res.errorCode()
          this.alert.visible = true
        }
      })
    }
  }
}
</script>