<style scoped>
.nav{
    height: 60px;
    line-height: 60px;
    color: #FFF;
    .logo{
        font-size: 18px;
    }
    a{
        color: #FFF;
    }
}
</style>

<template>
    <div class="layout">
        <Menu mode="horizontal" theme="dark" active-name="1" @on-select="turnUrl" style="background: #34495E">
            <div class="container nav">
                <Row>
                    <Col span="5" class="logo">
                        <router-link to="checkstand">考拉客房管理系统</router-link>
                    </Col>
                    <Col span="15">
                        <MenuItem name="checkstand">
                            客房登记
                        </MenuItem>
                        <MenuItem name="orderIn">
                            门店管理
                        </MenuItem>
                        <MenuItem name="personInfo">
                            系统设置
                        </MenuItem>
                        <MenuItem name="managerInfo">
                            平台管理
                        </MenuItem>
                    </Col>
                    <Col span="4" class="tr">
                        <Dropdown @on-click="turnUrl">
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
        </Menu>
        <div class="mb"></div>
        <div class="container">
            <router-view></router-view>
        </div>
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