<template>
	<Row>
        <Col span="5">
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
        </Col>
        <Col span="19">
            <router-view></router-view>
        </Col>
    </Row>
</template>

<script>
export default{}
</script>