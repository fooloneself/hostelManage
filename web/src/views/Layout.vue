<style scoped>
.layout-nav{
    height: 60px;
    line-height: 60px;
    color: #FFF;
    background: #34495E;
    min-width: 1208px;
    .logo{
        height: 60px;
        line-height: 60px;
        a{
            font-size: 18px;
            margin-left: 8px;
        }
    }
    a{
        color: #FFF;
        font-size: 14px;
    }
    .menu{
        width: 384px;
        margin: 0 auto;
        background: #34495E;
        z-index: auto;
    }
}
</style>

<template>
    <div>
        <div class="layout-nav">
            <div class="container">
                <Row>
                    <Col span="4">
                        <div class="logo">
                            <router-link to="">优客满客房管理系统</router-link>
                        </div>
                    </Col>
                    <Col span="16">
                        <Menu mode="horizontal" theme="dark" active-name="checkstand" @on-select="turnUrl" class="menu">
                            <MenuItem name="/checkstand">客房登记</MenuItem>
                            <MenuItem name="/orderIn">门店管理</MenuItem>
                            <MenuItem name="/personInfo">系统设置</MenuItem>
                            <MenuItem name="/managerInfo">平台管理</MenuItem>
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
                                <DropdownItem name="personInfo">个人资料</DropdownItem>
                                <DropdownItem name="personPassword">修改密码</DropdownItem>
                                <DropdownItem divided name="login">退出登录</DropdownItem>
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