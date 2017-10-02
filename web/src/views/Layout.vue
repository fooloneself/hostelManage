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
        <Menu mode="horizontal" :theme="theme" active-name="1" style="background: #34495E">
            <div class="container nav">
                <Row>
                    <Col span="5" class="logo">
                        考拉客房管理系统
                    </Col>
                    <Col span="15">
                        <MenuItem name="checkstand">
                            客房登记
                        </MenuItem>
                        <MenuItem name="store">
                            门店管理
                        </MenuItem>
                        <MenuItem name="system">
                            系统设置
                        </MenuItem>
                        <MenuItem name="platform">
                            平台管理
                        </MenuItem>
                    </Col>
                    <Col span="4" class="tr">
                        <Dropdown class="tl">
                            <a href="javascript:void(0)">
                                Admin Dun
                                <Icon type="arrow-down-b"></Icon>
                            </a>
                            <DropdownMenu slot="list">
                                <DropdownItem>个人资料</DropdownItem>
                                <DropdownItem>修改头像</DropdownItem>
                                <DropdownItem>修改密码</DropdownItem>
                                <DropdownItem divided>退出登录</DropdownItem>
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
        data () {
            return {
                theme: 'dark'
            }
        }
    }
</script>