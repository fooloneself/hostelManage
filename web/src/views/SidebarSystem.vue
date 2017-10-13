<template>
<div class="layout-body">
    <div class="fl">
        <Menu theme="light" active-name="personInfo" :open-names="['person']" @on-select="turnUrl">
            <MenuGroup name="person" title="个人中心">
                <MenuItem name="personInfo"><Icon type="record"></Icon>个人资料</MenuItem>
                <MenuItem name="personPassword"><Icon type="record"></Icon>修改密码</MenuItem>
                <MenuItem name="personNotice"><Icon type="record"></Icon>通知公告</MenuItem>
                <MenuItem name="personTips"><Icon type="record"></Icon>意见反馈</MenuItem>
            </MenuGroup>
            <MenuGroup name="room" title="房间配置">
                <MenuItem name="roomType"><Icon type="record"></Icon>房间类型</MenuItem>
                <MenuItem name="roomList"><Icon type="record"></Icon>房间列表</MenuItem>
            </MenuGroup>
            <MenuGroup name="config" title="功能配置">
                <MenuItem name="configStore"><Icon type="record"></Icon>门店配置</MenuItem>
                <MenuItem name="configChannel"><Icon type="record"></Icon>渠道配置</MenuItem>
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