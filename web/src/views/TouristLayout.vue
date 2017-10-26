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
        <router-view></router-view>
    </div>
</template>

<script>
export default{
    data () {
        return {
        }
    }
}
</script>
