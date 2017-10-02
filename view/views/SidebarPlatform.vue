<template>
	<Row>
        <Col span="5">
            <Menu :theme="'light'" active-name="1">
                <MenuGroup title="个人中心">
                    <MenuItem name="manager_info">
                        <Icon type="document-text"></Icon>
                        个人资料
                    </MenuItem>
                    <MenuItem name="manager_photo">
                        <Icon type="chatbubbles"></Icon>
                        修改头像
                    </MenuItem>
                    <MenuItem name="manager_password">
                        <Icon type="chatbubbles"></Icon>
                        修改密码
                    </MenuItem>
                </MenuGroup>
                <MenuGroup title="鉴权中心">
                    <MenuItem name="power_menu">
                        <Icon type="document-text"></Icon>
                        菜单配置
                    </MenuItem>
                    <MenuItem name="power_role">
                        <Icon type="chatbubbles"></Icon>
                        角色配置
                    </MenuItem>
                    <MenuItem name="power_account">
                        <Icon type="chatbubbles"></Icon>
                        账号管理
                    </MenuItem>
                </MenuGroup>
                <MenuGroup title="基础中心">
                    <MenuItem name="basic_dict">
                        <Icon type="document-text"></Icon>
                        数据字典
                    </MenuItem>
                    <MenuItem name="basic_linkage">
                        <Icon type="chatbubbles"></Icon>
                        联动菜单
                    </MenuItem>
                    <MenuItem name="basic_notice">
                        <Icon type="chatbubbles"></Icon>
                        通知公告
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