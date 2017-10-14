<style scoped>
.body_bg{
	position: absolute;
	top: 0;
	bottom: 0;
	right: 0;
	left: 0;
	background: #34495E;
}
.login{
	position: absolute;
	left: 50%;
	top: 50%;
	margin-left: -318px;
	margin-top: -140px;
	border-radius: 5px;
	.login_logo{
		padding: 40px 40px 40px;
		color: #FFF;
		.logo{
			font-size: 100px;
			text-align: center;
		}
		.title{
			font-size: 20px;
			text-align: center;
			font-weight: bolder;
			letter-spacing: 0.1em;
		}
	}
	.login_form{
		padding: 0 40px;
		border-left: 1px solid #40586F;
		color: #FFF;
		p{
			font-size: 16px;
			margin-bottom: 20px;
		}
		.input{
			border: none;
			border-radius: 0;
			border-bottom: 1px solid #40586F;
			background: transparent;
			padding: 0 0 0 10px;
			height: 40px;
			width: 300px;
			line-height: 40px;
			margin-bottom: 20px;
			color: #FFF;
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
			margin-bottom: 40px;
			border-bottom: 1px solid #40586F;
		}
		.btn_login{
			width: 100%;
			height: 40px;
			border-radius: 30px;
			margin-top: 20px;
			font-size: 14px;
		}
		a.link{
			color: #FFF;
		}
	}
}
</style>

<template>
	<div>
		<div class="body_bg"></div>
		<div class="login">
			<div class="login_logo fl">
				<div class="logo"><i class="fa fa-gg" aria-hidden="true"></i></div>
				<div class="title">考拉客房管理系统</div>
			</div>
			<div class="login_form fr">
				<p>登录 / Login</p>
				<form style="width: 300px;">
					<input v-model="userName" type="text" class="input" placeholder="请输入用户名">
					<input v-model="password" type="password" class="input" placeholder="请输入密码">
					<input v-model="code" type="text" class="input input_code" placeholder="请输入验证码">
					<span class="code">ASDAFG</span>
					<Button type="primary" @click='submit' long shape="circle">登录</Button>
					<div class="cls"></div>
				</form>
			</div>
			<div class="cls"></div>
		</div>
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
		    this.host.post('login',{'userName': this.userName,'password':this.password,'code': this.code}).then(function(res){
		        if(res.isSuccess()){
		            this.$router.push('checkstand');
		        }else{
		            alert(res.error());
		        }
		    })
		}
	}
}
</script>
