<template>
<div class="layout-body">
    <Row>
        <Col span="4">
            <Menu theme="light" active-name="/admin/configStore" @on-select="turnUrl" style="width: auto">
                <MenuGroup name="config" title="功能配置">
                    <MenuItem name="/admin/configStore">
                        <i class="fa fa-cog fa-fw" aria-hidden="true"></i>门店配置
                    </MenuItem>
                    <MenuItem name="/admin/configAccount">
                        <i class="fa fa-user-plus fa-fw" aria-hidden="true"></i>账号配置
                    </MenuItem>
                    <MenuItem name="/admin/configChannel">
                        <i class="fa fa-handshake-o fa-fw" aria-hidden="true"></i>自定义渠道
                    </MenuItem>
                </MenuGroup>
                <MenuGroup name="room" title="房间配置">
                    <MenuItem name="/admin/roomType">
                        <i class="fa fa-cubes fa-fw" aria-hidden="true"></i>房间类型
                    </MenuItem>
                    <MenuItem name="/admin/roomList">
                        <i class="fa fa-cube fa-fw" aria-hidden="true"></i>房间列表
                    </MenuItem>
                </MenuGroup>
                <MenuGroup name="person" title="个人中心">
                    <MenuItem name="/admin/personInfo">
                        <i class="fa fa-user fa-fw" aria-hidden="true"></i>个人资料
                    </MenuItem>
                    <MenuItem name="/admin/personPassword">
                        <i class="fa fa-lock fa-fw" aria-hidden="true"></i>修改密码
                    </MenuItem>
                    <MenuItem name="/admin/personNotice">
                        <i class="fa fa-bullhorn fa-fw" aria-hidden="true"></i>通知公告
                    </MenuItem>
                    <MenuItem name="/admin/personTips">
                        <i class="fa fa-send fa-fw" aria-hidden="true"></i>意见反馈
                    </MenuItem>
                </MenuGroup>
            </Menu>
        </Col>
        <Col span="20">
            <div class="container-content">
            <transition name="slideRight">
                <router-view></router-view>
            </transition>
            </div>
        </Col>
    </Row>
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