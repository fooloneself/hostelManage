<style scoped>
.layout-nav{
    height: 70px;
    line-height: 70px;
    background: rgba(44,62,80,1);
    min-width: 1280px;
    position: relative;
    z-index: 999;
    .logo{
        margin-left: 24px;
        height: 70px;
        line-height: 70px;
        img{
            height: 34px;
            margin: 18px 0;
        }
    }
    a{
        font-size: 14px;
        color: #FFF;
    }
}
.form{
	width: 360px;
    position: absolute;
    z-index: auto;
    left: 50%;
    top: 50%;
    margin-top: -180px;
    margin-left: -180px;
	.form-table{
		p{
			font-size: 18px;
			margin-bottom: 24px;
			font-weight: 600;
			letter-spacing: 1px;
		}
		.input{
	        height: 40px;
	        line-height: 40px;
	        border-bottom: 1px solid #dddee1;
	        position: relative;
	        margin-bottom: 16px;
	        input{
	        	background: transparent;
	        	border: none;
	        	width: 100%;
	        	padding-left: 8px;
	        }
	        .fa{
	        	position: absolute;
	        	right: 10px;
	        	top: 9px;
	        	font-size: 22px;
	        	color: #bbbec4;
	        }
	        .code{
	        	font-size: 16px;
	        	font-weight: bold;
	        	text-align: right;
	        	font-style: italic;
	        	color: #bbbec4;
	        }
		}
		a{
			color: #16a085;
		}
	}
}
.footer{
    position: absolute;
    bottom: 0px;
    height: 80px;
    line-height: 80px;
    width: 100%;
	&:before{
        content: "";
        display: block;
        width: 100%;
        height: 1px;
        background: #dddee1;
        position: absolute;
        top: 0;
        bottom: 0;
        right: 0;
    }
    .footer-info{
        width: 1000px;
        margin: 0 auto;
    }
}
</style>

<template>
<div>
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
					<div class="input">
						<input v-model="userName" type="text" placeholder="请输入用户名">
						<i class="fa fa-user" aria-hidden="true"></i>
					</div>
					<div class="input">
						<input v-model="password" type="password" placeholder="请输入密码">
						<i class="fa fa-lock" aria-hidden="true"></i>
					</div>
					<div class="input">
						<Row>
							<Col span="18"><input v-model="code" type="text" placeholder="请输入验证码"></Col>
							<Col span="6"><div class="code">ASDAFG</div></Col>
						</Row>
					</div>
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
    <div class="footer">
        <Row class="footer-info">
            <Col span="16">静静的为自己许下一个愿望，为此而努力，万一就实现了岂不是惊喜！</Col>
            <Col span="8" class="tr">Copyright@TwoBoys.</Col>
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
