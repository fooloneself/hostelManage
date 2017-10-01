<template>
	<div>
        <div class="main-menu fl">
            <Menu :theme="'light'" active-name="1">
                <MenuGroup title="个人中心">
                    <MenuItem name="person_info">
                        <Icon type="document-text"></Icon>
                        个人资料
                    </MenuItem>
                    <MenuItem name="person_photo">
                        <Icon type="chatbubbles"></Icon>
                        修改头像
                    </MenuItem>
                    <MenuItem name="person_password">
                        <Icon type="chatbubbles"></Icon>
                        修改密码
                    </MenuItem>
                    <MenuItem name="person_notice">
                        <Icon type="chatbubbles"></Icon>
                        通知公告
                    </MenuItem>
                    <MenuItem name="person_tips">
                        <Icon type="chatbubbles"></Icon>
                        意见反馈
                    </MenuItem>
                </MenuGroup>
                <MenuGroup title="房间配置">
                    <MenuItem name="room_type">
                        <Icon type="chatbubbles"></Icon>
                        房间类型
                    </MenuItem>
                    <MenuItem name="room_list">
                        <Icon type="chatbubbles"></Icon>
                        房间列表
                    </MenuItem>
                </MenuGroup>
                <MenuGroup title="功能配置">
                    <MenuItem name="config_store">
                        <Icon type="chatbubbles"></Icon>
                        门店配置
                    </MenuItem>
                    <MenuItem name="config_channel">
                        <Icon type="chatbubbles"></Icon>
                        渠道配置
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