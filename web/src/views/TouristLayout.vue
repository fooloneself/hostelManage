<style scoped>
.layout-nav{
    height: 60px;
    line-height: 60px;
    background: rgba(44,62,80,0.5);
    min-width: 1280px;
    position: relative;
    z-index: 999;
    .logo{
        margin-left: 24px;
        height: 60px;
        line-height: 60px;
        img{
            height: 30px;
            margin-top: 15px;
        }
    }
    a{
        font-size: 14px;
        color: #FFF;
    }
    .menu{
        background: #2C3E50;
        font-size: 16px;
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
                    <Col span="4" offset="16" class="tr" style="padding-right: 24px;">
                        <router-link to="register" style="margin-right: 16px;">注册</router-link>
                        <router-link to="login">登录</router-link>
                    </Col>
                </Row>
            </div>
        </div>
        <transition name="slideUp">
            <router-view></router-view>
        </transition>
    </div>
</template>

<script>
export default{
    methods:{
        turnUrl:function(name){
            this.$router.push(name);
        }
    }
}
</script>
