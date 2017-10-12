<template>
	<Row>
        <Col span="5">
            <Menu theme="light" active-name="1" @on-select="turnUrl">
                <MenuGroup title="个人中心">
                    <MenuItem name="personInfo">
                        <Icon type="document-text"></Icon>
                        个人资料
                    </MenuItem>
                    <MenuItem name="personPhoto">
                        <Icon type="chatbubbles"></Icon>
                        修改头像
                    </MenuItem>
                    <MenuItem name="personPassword">
                        <Icon type="chatbubbles"></Icon>
                        修改密码
                    </MenuItem>
                    <MenuItem name="personNotice">
                        <Icon type="chatbubbles"></Icon>
                        通知公告
                    </MenuItem>
                    <MenuItem name="personTips">
                        <Icon type="chatbubbles"></Icon>
                        意见反馈
                    </MenuItem>
                </MenuGroup>
                <MenuGroup title="房间配置">
                    <MenuItem name="roomType">
                        <Icon type="chatbubbles"></Icon>
                        房间类型
                    </MenuItem>
                    <MenuItem name="roomList">
                        <Icon type="chatbubbles"></Icon>
                        房间列表
                    </MenuItem>
                </MenuGroup>
                <MenuGroup title="功能配置">
                    <MenuItem name="configStore">
                        <Icon type="chatbubbles"></Icon>
                        门店配置
                    </MenuItem>
                    <MenuItem name="configChannel">
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
export default{
    methods:{
        turnUrl:function(name){
            this.$router.push(name);
        }
    }
}
</script>