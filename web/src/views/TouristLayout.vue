<style scoped>
.layout-nav{
    height: 60px;
    line-height: 60px;
    background: #2C3E50;
    min-width: 1280px;
    position: relative;
    z-index: 999;
    .logo{
        margin-left: 24px;
        height: 60px;
        line-height: 60px;
        img{
            height: 26px;
            margin-top: 17px;
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
        <div class="body_bg"></div>
        <div class="layout-nav">
            <div class="container-body">
                <Row>
                    <Col span="4">
                        <div class="logo">
                            <router-link to="/">
                                <img src="../images/logo-white.png" alt="">
                            </router-link>
                        </div>
                    </Col>
                    <!-- <Col span="16">
                        <Menu mode="horizontal" active-name="index" @on-select="turnUrl" class="menu">
                            <MenuItem name="index">首页</MenuItem>
                        </Menu>
                    </Col> -->
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
