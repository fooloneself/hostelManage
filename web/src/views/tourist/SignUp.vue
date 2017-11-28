<style scoped>
.form-container{
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	background: #2C3E50;
}
.form{
	width: 680px;
    position: absolute;
    z-index: auto;
    left: 50%;
    top: 50%;
    margin-top: -154px;
    margin-left: -340px;
	color: #ffffff;
	.form-logo{
		float: right;
		text-align: center;
		padding-right: 40px;
		img{
			width: 100px;
			margin-bottom: 24px;
		}
		p{
			font-size: 25px;
			font-weight: 500;
			letter-spacing: 0.1em;
		}
	}
	.form-table{
		padding-left: 40px;
		border-left: 1px solid #34495E;
		p{
			font-size: 16px;
			margin-bottom: 20px;
		}
		.input{
			border: none;
			color: #ffffff;
			border-radius: 0;
			border-bottom: 1px solid #34495E;
			background: transparent;
			padding: 0 0 0 10px;
			height: 40px;
			line-height: 40px;
			font-size: 14px;
			margin-bottom: 20px;
			width: 100%;
		}
		.code{
			height: 40px;
			line-height: 40px;
			text-align: center;
			font-style: italic;
			font-weight: bolder;
			font-size: 14px;
			border-bottom: 1px solid #34495E;
		}
		a{
			color: #16a085;
		}
	}
}
</style>

<template>
<div class="form-container">
	<div class="form">
		<Row type="flex" align="middle">
			<Col span="11">
				<div class="form-logo">
					<img src="/src/images/logo.png" alt="">
					<p>优客满客房管理系统</p>
				</div>
			</Col>
			<Col span="13">
				<form class="form-table">
					<p>注册 / Sign Up </p>
					<input v-model="userName" type="text" class="input" placeholder="请输入用户名">
					<input v-model="password" type="password" class="input" placeholder="请输入密码">
					<input v-model="password" type="password" class="input" placeholder="请再次输入密码">
					<Button size="large" type="primary" @click='submit' long shape="circle" class="mt">注册</Button>
					<div class="mb"></div>
					<Row>
						<Col span="12"><router-link to="login">已有帐号？点击登录</router-link></Col>
						<Col span="12" class="tr"><router-link to="/">返回首页</router-link></Col>
					</Row>
				</form>
			</Col>
		</Row>
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
    }
}
</script>
