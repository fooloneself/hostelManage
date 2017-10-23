<style scoped>
.layout-nav{
    height: 60px;
    line-height: 60px;
    border-bottom: 1px solid #dddee1;
    min-width: 1208px;
    background: #FFF;
    position: relative;
    z-index: auto;
    a{
        font-size: 14px;
        color: #16A085;
    }
    .logo{
        height: 60px;
        line-height: 60px;
        img{
        	height: 26px;
        	margin-top: 17px;
        }
    }
    .menu{
        height: 60px;
        line-height: 60px;    	
    }
}
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
	<div>
		<div class="body_bg"></div>
		<div class="layout-nav">
		    <div class="container-body">
		        <Row>
		            <Col span="4">
		                <div class="logo">
		                    <router-link to="">
								<img src="../images/logo.png" alt="">
		                    </router-link>
		                </div>
		            </Col>
		            <Col span="16">
		                <Menu mode="horizontal" active-name="Index" class="menu">
		                    <MenuItem name="Index">首页</MenuItem>
		                    <MenuItem name="Produce">产品介绍</MenuItem>
		                    <MenuItem name="Case">成功案例</MenuItem>
		                </Menu>
		            </Col>
		            <Col span="4" class="tr">
			            <router-link to="register" style="margin-right: 16px;">注册</router-link>
			            <router-link to="login">登录</router-link>
		            </Col>
		        </Row>
		    </div>
		</div>
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
		            this.$router.push('checkstand');
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
