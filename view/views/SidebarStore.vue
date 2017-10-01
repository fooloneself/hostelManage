<template>
	<div>
        <div class="main-menu fl">
            <Menu :theme="'light'" active-name="1">
                <MenuGroup title="订单管理">
                    <MenuItem name="order_in">
                        <Icon type="document-text"></Icon>
                        今日新办
                    </MenuItem>
                    <MenuItem name="order_out">
                        <Icon type="chatbubbles"></Icon>
                        即将离店
                    </MenuItem>
                    <MenuItem name="order_all">
                        <Icon type="chatbubbles"></Icon>
                        全部订单
                    </MenuItem>
                    <MenuItem name="order_error">
                        <Icon type="chatbubbles"></Icon>
                        异常订单
                    </MenuItem>
                </MenuGroup>
                <MenuGroup title="会员管理">
                    <MenuItem name="member_rank">
                        <Icon type="document-text"></Icon>
                        会员等级
                    </MenuItem>
                    <MenuItem name="member_list">
                        <Icon type="chatbubbles"></Icon>
                        会员列表
                    </MenuItem>
                </MenuGroup>
            </Menu>
        </div>
        <div class="main-body fr">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
export default{}
</script>