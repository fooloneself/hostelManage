<template>
<div class="layout-body">
    <div class="fl">
        <Menu theme="light" active-name="managerInfo" @on-select="turnUrl">
            <MenuGroup name="manager" title="个人中心">
                <MenuItem name="managerInfo"><Icon type="record"></Icon>个人资料</MenuItem>
                <MenuItem name="managerPassword"><Icon type="record"></Icon>修改密码</MenuItem>
            </MenuGroup>
            <MenuGroup name="power" title="鉴权中心">
                <MenuItem name="powerMenu"><Icon type="record"></Icon>菜单配置</MenuItem>
                <MenuItem name="powerRole"><Icon type="record"></Icon>角色配置</MenuItem>
                <MenuItem name="powerAccount"><Icon type="record"></Icon>账号管理</MenuItem>
            </MenuGroup>
            <MenuGroup name="basic" title="基础中心">
                <MenuItem name="basicDict"><Icon type="record"></Icon>数据字典</MenuItem>
                <MenuItem name="basicLinkage"><Icon type="record"></Icon>联动菜单</MenuItem>
                <MenuItem name="basicNotice"><Icon type="record"></Icon>通知公告</MenuItem>
            </MenuGroup>
        </Menu>
    </div>
    <div class="main">
        <router-view></router-view>
    </div>
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