<template>
<div class="layout-body">
    <div class="fl">
        <Menu theme="light" active-name="orderIn"@on-select="turnUrl">
            <MenuGroup name="order" title="订单管理">
                <MenuItem name="orderIn"><Icon type="record"></Icon>今日新办</MenuItem>
                <MenuItem name="orderOut"><Icon type="record"></Icon>即将离店</MenuItem>
                <MenuItem name="orderAll"><Icon type="record"></Icon>全部订单</MenuItem>
                <MenuItem name="orderError"><Icon type="record"></Icon>异常订单</MenuItem>
            </MenuGroup>
            <MenuGroup name="member" title="会员管理">
                <MenuItem name="memberRank"><Icon type="record"></Icon>会员等级</MenuItem>
                <MenuItem name="memberList"><Icon type="record"></Icon>会员列表</MenuItem>
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