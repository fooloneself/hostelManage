<style scoped>
.layout-nav{
    height: 60px;
    line-height: 60px;
    border-bottom: 1px solid #dddee1;
    background: #FFF;
    min-width: 1208px;
    position: relative;
    z-index: auto;
    .logo{
        height: 60px;
        line-height: 60px;
        img{
            height: 26px;
            margin-top: 17px;
        }
    }
    a{
        font-size: 14px;
        color: #16A085;
    }
    .menu{
        background: #FFF;
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
                                <img src="/src/images/logo.png" alt="">
                            </router-link>
                        </div>
                    </Col>
                    <Col span="16">
                        <Menu mode="horizontal" active-name="/admin/checkstand" @on-select="turnUrl" class="menu">
                            <MenuItem name="/admin/checkstand">客房登记</MenuItem>
                            <MenuItem name="/admin/orderIn">渠道订单</MenuItem>
                            <MenuItem name="/admin/memberList">营销管理</MenuItem>
                            <MenuItem name="/admin/configStore">系统配置</MenuItem>
                            <MenuItem name="/admin/basicTips">平台管理</MenuItem>
                        </Menu>
                    </Col>
                    <Col span="4" class="tr">
                        <Badge dot>
                            <router-link to=""><i class="fa fa-bell-o fa-lg" aria-hidden="true"></i></router-link>
                        </Badge>
                        <Dropdown @on-click="turnUrl" style="margin-left: 8px;">
                            <a href="javascript:void(0)">
                                Admin Dun
                                <Icon type="arrow-down-b"></Icon>
                            </a>
                            <DropdownMenu slot="list" class="tl">
                                <DropdownItem name="/admin/personInfo">个人资料</DropdownItem>
                                <DropdownItem name="/admin/personPassword">修改密码</DropdownItem>
                                <DropdownItem divided name="/login">退出登录</DropdownItem>
                            </DropdownMenu>
                        </Dropdown>
                    </Col>
                </Row>
            </div>
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        methods:{
            turnUrl:function(name){
                this.$router.push(name);
            }
        }
    }
</script>