<style scoped>
.layout-nav{
    height: 70px;
    line-height: 70px;
    background: rgba(44,62,80,0.5);
    min-width: 1280px;
    position: relative;
    z-index: 999;
    .logo{
        margin-left: 24px;
        height: 70px;
        line-height: 70px;
        img{
            height: 38px;
            margin-top: 16px;
        }
    }
    a{
        font-size: 14px;
        color: #FFF;
    }
}
.form-container{
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	background: #2C3E50;
}
.form{
	width: 360px;
    position: absolute;
    z-index: auto;
    left: 50%;
    top: 50%;
    margin-top: -154px;
    margin-left: -180px;
	color: #ffffff;
	.form-table{
		p{
			font-size: 16px;
			margin-bottom: 20px;
			letter-spacing: 0.1em;
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
	<div class="layout-nav">
        <div class="container-body">
            <Row>
                <Col span="4">
                    <div class="logo">
                        <router-link to="/">
                            <img src="/src/images/logo.png" alt="">
                        </router-link>
                    </div>
                </Col>
            </Row>
        </div>
    </div>
	<div class="form">
		<Row>
			<Col span="24">
				<form class="form-table">
					<p>登录 / Sign In</p>
					<input v-model="userName" type="text" class="input" placeholder="请输入用户名">
					<input v-model="password" type="password" class="input" placeholder="请输入密码">
					<Row>
						<Col span="16"><input v-model="code" type="text" class="input input_code" placeholder="请输入验证码"></Col>
						<Col span="8"><div class="code">ASDAFG</div></Col>
					</Row>
					<Button size="large" type="primary" @click='submit' long shape="circle" class="mt">登&nbsp;&nbsp;录</Button>
					<div class="mb"></div>
					<Row>
						<Col span="12"><router-link to="register">没有帐号？免费注册</router-link></Col>
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
    },
	methods:{
		submit:function(){
		    var that=this;
		    this.host.post('login',{'userName': this.userName,'password':this.password,'code': this.code}).then(function(res){
		        if(res.isSuccess()){
		            this.host.setSession(res.data().id,that.userName,res.data().token)
		            this.$router.push('/admin');
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
