<style scoped>
.layout-nav{
    background: rgba(44,62,80,.7);
    position: fixed;
    padding: 0 24px;
    width: 100%;
    z-index: 999;
    .logo{
        height: 70px;
        line-height: 70px;
        img{
            height: 34px;
            margin: 18px 0;
        }
    }
    a{
        line-height: 70px;
        font-size: 14px;
        color: #FFF;
        &.login{
            display: inline-block;
            padding: 0 24px;
            background: #16A085;
            margin-right: 24px;
        }
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
                    <Col span="4" offset="16" class="tr">
                        <router-link to="login" class="login">登录</router-link>
                        <router-link to="register">注册</router-link>
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
