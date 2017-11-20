<style scoped>
.layout-nav{
    height: 61px;
    line-height: 61px;
    background: #2C3E50;
    min-width: 1280px;
    position: relative;
    z-index: 999;
    .logo{
        margin-left: 24px;
        height: 61px;
        line-height: 61px;
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
                            <router-link to="/admin">
                                <img src="/src/images/logo-white.png" alt="">
                            </router-link>
                        </div>
                    </Col>
                    <Col span="16">
                        <Menu mode="horizontal" active-name="/admin/checkstand" @on-select="turnUrl" class="menu" theme="dark">
                            <MenuItem name="/admin/checkstand">客房登记</MenuItem>
                            <MenuItem name="/admin/orderIn">渠道订单</MenuItem>
                            <MenuItem name="/admin/memberList">营销管理</MenuItem>
                            <MenuItem name="/admin/configStore">系统配置</MenuItem>
                            <MenuItem name="/admin/basicTips">平台管理</MenuItem>
                        </Menu>
                    </Col>
                    <Col span="4" style="padding-right: 24px;">
                        <div class="fr">
                            <Badge dot>
                                <router-link to=""><i class="fa fa-bell-o fa-lg" aria-hidden="true"></i></router-link>
                            </Badge>
                            <Dropdown @on-click="turnUrl" class="icon-ml">
                                <a href="javascript:void(0)">
                                    {{userName}}
                                    <Icon type="arrow-down-b"></Icon>
                                </a>
                                <DropdownMenu slot="list" class="tl">
                                    <DropdownItem name="/admin/personInfo">个人资料</DropdownItem>
                                    <DropdownItem name="/admin/managerPassword/0">修改密码</DropdownItem>
                                    <DropdownItem name="/login" divided @click="logout">退出登录</DropdownItem>
                                </DropdownMenu>
                            </Dropdown>
                        </div>
                    </Col>
                </Row>
            </div>
        </div>
        <div class="layout-body">
            <transition name="slideUp">
                <router-view></router-view>
            </transition>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                userName: this.host.getUserName()
            };
        },
        methods:{
            turnUrl:function(name){
                this.$router.push(name);
            },
            logout (){
                this.host.post('loginOut').then(function(res){
                    if(res.isSuccess()){

                        this.$router.push('/login');
                    }else{
                        this.$Notice.info({
                            title: '错误提示',
                            desc: res.error()
                        })
                    }
                })
            }
        }
    }
</script>