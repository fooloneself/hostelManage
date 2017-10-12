<template>
	<Row>
        <Col span="5">
            <Menu theme="light" active-name="1" @on-select="turnUrl">
                <MenuGroup title="个人中心">
                    <MenuItem name="managerInfo">
                        <Icon type="document-text"></Icon>
                        个人资料
                    </MenuItem>
                    <MenuItem name="managerPhoto">
                        <Icon type="chatbubbles"></Icon>
                        修改头像
                    </MenuItem>
                    <MenuItem name="managerPassword">
                        <Icon type="chatbubbles"></Icon>
                        修改密码
                    </MenuItem>
                </MenuGroup>
                <MenuGroup title="鉴权中心">
                    <MenuItem name="powerMenu">
                        <Icon type="document-text"></Icon>
                        菜单配置
                    </MenuItem>
                    <MenuItem name="powerRole">
                        <Icon type="chatbubbles"></Icon>
                        角色配置
                    </MenuItem>
                    <MenuItem name="powerAccount">
                        <Icon type="chatbubbles"></Icon>
                        账号管理
                    </MenuItem>
                </MenuGroup>
                <MenuGroup title="基础中心">
                    <MenuItem name="basicDict">
                        <Icon type="document-text"></Icon>
                        数据字典
                    </MenuItem>
                    <MenuItem name="basicLinkage">
                        <Icon type="chatbubbles"></Icon>
                        联动菜单
                    </MenuItem>
                    <MenuItem name="basicNotice">
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
export default{
    methods:{
        turnUrl:function(name){
            this.$router.push(name);
        }
    }
}
</script>