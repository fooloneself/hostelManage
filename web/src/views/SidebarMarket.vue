<template>
<div class="layout-body">
    <Row>
        <Col span="4">
            <Menu theme="light" active-name="/admin/memberList" @on-select="turnUrl" style="width: auto">
                <MenuGroup name="member" title="会员管理">
                    <MenuItem name="/admin/memberRank"><i class="fa fa-users fa-fw" aria-hidden="true"></i>会员等级</MenuItem>
                    <MenuItem name="/admin/memberList"><i class="fa fa-user-plus fa-fw" aria-hidden="true"></i>会员列表</MenuItem>
                    <MenuItem name="/admin/memberBlack"><i class="fa fa-warning fa-fw" aria-hidden="true"></i>黑名单</MenuItem>
                </MenuGroup>
                <MenuGroup name="promotion" title="活动管理">
                    <MenuItem name="/admin/discount"><i class="fa fa-rmb fa-fw" aria-hidden="true"></i>折扣</MenuItem>
                    <MenuItem name="/admin/cutdown"><i class="fa fa-money fa-fw" aria-hidden="true"></i>满减</MenuItem>
                    <MenuItem name="/admin/bargain"><i class="fa fa-bolt fa-fw" aria-hidden="true"></i>特价房</MenuItem>
                    <MenuItem name="/admin/coupon"><i class="fa fa-ticket fa-fw" aria-hidden="true"></i>优惠券</MenuItem>
                    <MenuItem name="/admin/gift"><i class="fa fa-gift fa-fw" aria-hidden="true"></i>抽奖</MenuItem>
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