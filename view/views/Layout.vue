<style scoped>
    .layout-logo{
        height: 60px;
        line-height: 60px;
        color: #FFF;
        font-size: 18px;
    }
    .layout-nav{
        margin-left: 60px;
    }
    .layout-body{
        margin-top: 40px;
    }
</style>

<template>
    <div class="layout">
        <Menu mode="horizontal" :theme="theme" active-name="1">
        <div class="container">
            <div class="layout-logo fl">
                考拉客房管理系统
            </div>
            <div class="layout-nav fl">
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
                <div class="cls"></div>
            </div>
        </div>
        </Menu>
        <div class="layout-body">
            <div class="container">
                <router-view></router-view>
            </div>
            <div class="cls"></div>
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