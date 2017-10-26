<style scoped>
.login_form{
	padding: 0 40px;
	background: #FFF;
    padding: 40px;
    border-radius: 5px;
    position: absolute;
    z-index: auto;
    left: 50%;
    top: 50%;
    margin-top: -224px;
    margin-left: -190px;
	p{
		font-size: 16px;
		margin-bottom: 20px;
	}
	.input{
		border: none;
		border-radius: 0;
		border-bottom: 1px solid #dddee1;
		background: transparent;
		padding: 0 0 0 10px;
		height: 40px;
		width: 300px;
		line-height: 40px;
		font-size: 14px;
		margin-bottom: 20px;
		&.input_code{
			float: left;
			width: 200px;
		}
	}
	.code{
		float: left;
		width: 100px;
		height: 40px;
		line-height: 40px;
		text-align: center;
		font-style: italic;
		font-weight: bolder;
		font-size: 14px;
		border-bottom: 1px solid #dddee1;
	}
	.btn_login{
		width: 100%;
		height: 40px;
		border-radius: 30px;
		margin-top: 20px;
		font-size: 14px;
	}
	a{
		color: #16a085;
	}
}
</style>

<template>
<div class="login_form">
	<p>登录 / Sign In</p>
	<form style="width: 300px;">
		<input v-model="userName" type="text" class="input" placeholder="请输入用户名">
		<input v-model="password" type="password" class="input" placeholder="请输入密码">
		<input v-model="code" type="text" class="input input_code" placeholder="请输入验证码">
		<span class="code">ASDAFG</span>
		<Button type="primary" @click='submit' long shape="circle" style="margin-top: 10px">登录</Button>
		<div class="mb"></div>
		<router-link to="register">没有帐号？免费注册</router-link>
		<div class="cls"></div>
	</form>
</div>
</template>

<script>
export default{
    data () {
        return {
          userName: '',
          password: '',
          code: ''
        }
    },
	methods:{
		submit:function(){
		    var that=this;
		    this.host.post('login',{'userName': this.userName,'password':this.password,'code': this.code}).then(function(res){
		        if(res.isSuccess()){
		            this.host.setSession(res.data().id,that.userName,res.data().token)
		            this.$router.push('/admin/checkstand');
		        }else{
		            this.$Notice.info({
	                    title: '提示',
	                    desc: res.error()
	                });
		        }
		    })
		}
	}
}
</script>
