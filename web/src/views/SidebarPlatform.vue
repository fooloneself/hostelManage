<template>
<div class="layout-body">
    <Row>
        <Col span="4">
            <Menu theme="light" active-name="managerInfo" @on-select="turnUrl" style="width: auto">
                <MenuGroup name="manager" title="个人中心">
                    <MenuItem name="managerInfo">
                        <i class="fa fa-user fa-fw" aria-hidden="true"></i>个人资料
                    </MenuItem>
                    <MenuItem name="managerPassword">
                        <i class="fa fa-lock fa-fw" aria-hidden="true"></i>修改密码
                    </MenuItem>
                </MenuGroup>
                <MenuGroup name="power" title="鉴权中心">
                    <MenuItem name="powerMenu">
                        <i class="fa fa-map fa-fw" aria-hidden="true"></i>菜单配置
                    </MenuItem>
                    <MenuItem name="powerRole">
                        <i class="fa fa-key fa-fw" aria-hidden="true"></i>角色配置
                    </MenuItem>
                    <MenuItem name="powerAccount">
                        <i class="fa fa-id-card fa-fw" aria-hidden="true"></i>账号管理
                    </MenuItem>
                </MenuGroup>
                <MenuGroup name="basic" title="基础中心">
                    <MenuItem name="basicDict">
                        <i class="fa fa-tag fa-fw" aria-hidden="true"></i>数据字典
                    </MenuItem>
                    <MenuItem name="basicLinkage">
                        <i class="fa fa-tags fa-fw" aria-hidden="true"></i>联动菜单
                    </MenuItem>
                    <MenuItem name="basicNotice">
                        <i class="fa fa-bullhorn fa-fw" aria-hidden="true"></i>通知公告
                    </MenuItem>
                </MenuGroup>
            </Menu>
        </Col>
        <Col span="20">
            <div class="container-content">
                <router-view></router-view>
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