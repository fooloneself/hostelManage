<template>
<div class="layout-body">
    <Row>
        <Col span="4">
            <Menu theme="light" active-name="/admin/orderIn" @on-select="turnUrl" style="width: auto">
                <MenuGroup name="order" title="订单管理">
                    <MenuItem name="/admin/orderIn">
                        <i class="fa fa-calendar-plus-o fa-fw" aria-hidden="true"></i>今日新办
                    </MenuItem>
                    <MenuItem name="/admin/orderOut">
                        <i class="fa fa-calendar-minus-o fa-fw" aria-hidden="true"></i>即将离店
                    </MenuItem>
                    <MenuItem name="/admin/orderAll">
                        <i class="fa fa-calendar-o fa-fw" aria-hidden="true"></i>全部订单
                    </MenuItem>
                    <MenuItem name="/admin/orderError">
                        <i class="fa fa-calendar-times-o fa-fw" aria-hidden="true"></i>异常订单
                    </MenuItem>
                </MenuGroup>
                <MenuGroup name="member" title="会员管理">
                    <MenuItem name="/admin/memberRank"><i class="fa fa-users fa-fw" aria-hidden="true"></i>会员等级</MenuItem>
                    <MenuItem name="/admin/memberList"><i class="fa fa-user-plus fa-fw" aria-hidden="true"></i>会员列表</MenuItem>
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