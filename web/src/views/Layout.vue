<style scoped>
.layout-nav{
    height: 60px;
    line-height: 60px;
    color: #FFF;
    background: #34495E;
    .logo{
        height: 60px;
        line-height: 60px;
        .ivu-icon{
            font-size: 30px;
            position: relative;
            top: 3px;
        }
        a{
            font-size: 18px;
            margin-left: 8px;
        }
    }
    a{
        color: #FFF;
    }
    .menu{
        width: 450px;
        margin: 0 auto;
        background: #34495E;
    }
}
</style>

<template>
    <div class="layout">
        <div class="layout-nav">
            <div class="container">
                <Row>
                    <Col span="4">
                        <div class="logo">
                            <Icon type="cube"></Icon>
                            <router-link to="">考拉客房管理系统</router-link>
                        </div>
                    </Col>
                    <Col span="16">
                        <Menu mode="horizontal" theme="dark" active-name="checkstand" @on-select="turnUrl" class="menu">
                            <MenuItem name="checkstand">
                                <Icon type="home"></Icon>客房登记
                            </MenuItem>
                            <MenuItem name="orderIn">
                                <Icon type="home"></Icon>门店管理
                            </MenuItem>
                            <MenuItem name="personInfo">
                                <Icon type="home"></Icon>系统设置
                            </MenuItem>
                            <MenuItem name="managerInfo">
                                <Icon type="home"></Icon>平台管理
                            </MenuItem>
                        </Menu>
                    </Col>
                    <Col span="4" class="tr">
                        <Dropdown @on-click="turnUrl" style="margin-left: 8px;">
                            <a href="javascript:void(0)">
                                Admin Dun
                                <Icon type="arrow-down-b"></Icon>
                            </a>
                            <DropdownMenu slot="list">
                                <DropdownItem name="personInfo">个人资料</DropdownItem>
                                <DropdownItem name="personPhoto">修改头像</DropdownItem>
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