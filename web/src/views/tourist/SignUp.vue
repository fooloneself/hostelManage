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
    margin-top: 60px;
    .container{
        width: 500px;
        margin: 0 auto;
    }
    a{
        color: #16A085;
    }
    p{
        font-size: 18px;
        font-weight: 600;
        letter-spacing: 1px;
        text-align: center;
    }
    .step{
        margin: 60px auto;
        width: 1000px;
        padding-left: 210px;
    }
    .successs{
        text-align: center;
        .fa{
            font-size: 68px;
            color: #16A085;
        }
        .info{
            font-size: 20px;
            line-height: 32px;
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
        <div class="container">
            <p>注册 / Sign Up </p>
        </div>
        <div class="step">
            <Steps :current="current" size="small">
                <Step title="登陆账号"></Step>
                <Step title="酒店信息"></Step>
                <Step title="完成注册"></Step>
            </Steps>
        </div>
        <div class="container" v-if="current==0">
            <Form v-model="form" :label-width="80" class="form-table">
                <FormItem label="用户名：">
                    <Input v-model="form.userName" type="text" placeholder="请输入用户名"></Input>
                </FormItem>
                <FormItem label="密码：">
                    <Input v-model="form.password" type="password" placeholder="请输入密码"></Input>
                </FormItem>
                <FormItem label="重复密码：">
                    <Input v-model="form.confirmPassword" type="password" placeholder="请再次输入密码"></Input>
                </FormItem>
                <FormItem class="tr">
                    <Button type="primary" @click='next' class="mt">下&nbsp;一&nbsp;步</Button>
                </FormItem>
                <div class="mb"></div>
                <Row>
                    <Col span="12"><router-link to="login">已有帐号？点击登录</router-link></Col>
                    <Col span="12" class="tr"><router-link to="/">返回首页</router-link></Col>
                </Row>
            </Form>
        </div>
        <div class="container" v-else-if="current==1">
            <Form v-model="form" :label-width="100" class="form-table">
                <FormItem label="门店名称：">
                    <Input v-model="form.userName" type="text" placeholder="请输入门店名称"></Input>
                </FormItem>
                <FormItem label="联系人：">
                    <Input v-model="form.password" type="text" placeholder="请输入联系人"></Input>
                </FormItem>
                <FormItem label="联系方式：">
                    <Input v-model="form.confirmPassword" type="text" placeholder="请输入联系方式"></Input>
                </FormItem>
                <FormItem label="每日退房时间：">
                    <TimePicker v-model="form.confirmPassword" type="time" placeholder="请输入退房时间"></TimePicker>
                </FormItem>
                <FormItem class="tr">
                    <Button type="primary" @click='pre' class="mt icon-mr">上&nbsp;一&nbsp;步</Button>
                    <Button type="primary" @click='next' class="mt">下&nbsp;一&nbsp;步</Button>
                </FormItem>
                <div class="mb"></div>
                <Row>
                    <Col span="12"><router-link to="login">已有帐号？点击登录</router-link></Col>
                    <Col span="12" class="tr"><router-link to="/">返回首页</router-link></Col>
                </Row>
            </Form>
        </div>
        <div class="container" v-else-if="current==2">
            <Row class="successs" type="flex" justify="center" align="middle">
                <Col span="6"><i class="fa fa-check" aria-hidden="true"></i></Col>
                <Col span="18">
                    <span class="info">恭喜您，账号注册成功，<br/><router-link to="/login">点击马上登陆！</router-link></span>
                </Col>
            </Row>
        </div>
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
            form:{
                userName: '',
                password: '',
                confirmPassword:''
            },
            current: 0
        }
    },
    methods:{
        pre(){
            this.current--;
        },
        next(){
            this.current++;
        }
    }
}
</script>
