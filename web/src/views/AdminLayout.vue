<style scoped>
.layout{
    min-width: 1280px;
    .layout-header{
        padding: 0 24px;
        height: 60px;
        line-height: 60px;
        background: #2C3E50;
        font-size: 14px;
        a{
            color: #FFF;
        }
        img{
            height: 24px;
            margin-top: 18px;
        }
    }
    .layout-left{
        width: 220px;
        position: absolute;
        left: 0px;
        top: 60px;
        bottom: 0px;
        &:after{
            content: "";
            display: block;
            width: 1px;
            background: #dddee1;
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
        }
    }
    .layout-right{
        position: absolute;
        left: 220px;
        top: 60px;
        right: 0;
        bottom: 0;
        background: #FFF;
        padding: 24px;
        min-width: 1060px;
        overflow-y: auto;
    }
}
</style>
<template>
    <div class="layout">
        <div class="layout-header">
            <router-link to="/admin">
                <img src="/src/images/logo-white.png" alt="">
            </router-link>
            <div class="fr">
                <Badge dot>
                    <router-link to=""><i class="fa fa-bell-o fa-lg" aria-hidden="true"></i></router-link>
                </Badge>
                <Dropdown @on-click="turnUrl" class="icon-ml">
                    <a href="javascript:void(0)">
                        {{userName}}
                        <Icon type="arrow-down-b" class="icon-ml"></Icon>
                    </a>
                    <DropdownMenu slot="list" class="tl">
                        <DropdownItem name="/admin/personInfo">个人资料</DropdownItem>
                        <DropdownItem name="/admin/managerPassword/0">修改密码</DropdownItem>
                        <DropdownItem name="/login" divided @click="logout">退出登录</DropdownItem>
                    </DropdownMenu>
                </Dropdown>
            </div>
        </div>
        <div class="layout-left">
            <Menu theme="light" style="width: auto" active-name="/admin/checkstand" @on-select="turnUrl" accordion>
                <MenuItem name="/admin"><i class="fa fa-check-square-o fa-fw" aria-hidden="true"></i>客房登记</MenuItem>
                <Submenu name="power">
                    <template slot="title"><i class="fa fa-compass fa-fw" aria-hidden="true"></i>鉴权中心</template>
                    <MenuItem name="/admin/powerMenu">
                        <i class="fa fa-map fa-fw" aria-hidden="true"></i>菜单配置
                    </MenuItem>
                    <MenuItem name="/admin/powerRole">
                        <i class="fa fa-key fa-fw" aria-hidden="true"></i>角色配置
                    </MenuItem>
                    <MenuItem name="/admin/powerAccount">
                        <i class="fa fa-id-card fa-fw" aria-hidden="true"></i>账号管理
                    </MenuItem>
                </Submenu>
                <Submenu name="basic">
                    <template slot="title"><i class="fa fa-cogs fa-fw" aria-hidden="true"></i>基础中心</template>
                    <MenuItem name="/admin/basicDict">
                        <i class="fa fa-tag fa-fw" aria-hidden="true"></i>数据字典
                    </MenuItem>
                    <MenuItem name="/admin/basicLinkage">
                        <i class="fa fa-tags fa-fw" aria-hidden="true"></i>联动菜单
                    </MenuItem>
                    <MenuItem name="/admin/basicNotice">
                        <i class="fa fa-bullhorn fa-fw" aria-hidden="true"></i>通知公告
                    </MenuItem>
                    <MenuItem name="/admin/basicTips">
                        <i class="fa fa-send fa-fw" aria-hidden="true"></i>意见反馈
                    </MenuItem>
                </Submenu>
                <Submenu name="manager">
                    <template slot="title"><i class="fa fa-user-o fa-fw" aria-hidden="true"></i>个人中心</template>
                    <MenuItem name="/admin/managerInfo">
                        <i class="fa fa-info fa-fw" aria-hidden="true"></i>个人资料
                    </MenuItem>
                    <MenuItem name="/admin/managerPassword/0">
                        <i class="fa fa-lock fa-fw" aria-hidden="true"></i>修改密码
                    </MenuItem>
                </Submenu>
                <Submenu name="order">
                    <template slot="title"><i class="fa fa-calendar fa-fw" aria-hidden="true"></i>订单管理</template>
                    <MenuItem name="/admin/orderIn">
                        <i class="fa fa-calendar-plus-o fa-fw" aria-hidden="true"></i>今日到店
                    </MenuItem>
                    <MenuItem name="/admin/orderOut">
                        <i class="fa fa-calendar-minus-o fa-fw" aria-hidden="true"></i>今日离店
                    </MenuItem>
                    <MenuItem name="/admin/orderFuture">
                        <i class="fa fa-calendar-check-o fa-fw" aria-hidden="true"></i>预订订单
                    </MenuItem>
                    <MenuItem name="/admin/orderAll">
                        <i class="fa fa-calendar-o fa-fw" aria-hidden="true"></i>全部订单
                    </MenuItem>
                    <MenuItem name="/admin/orderError">
                        <i class="fa fa-calendar-times-o fa-fw" aria-hidden="true"></i>异常订单
                    </MenuItem>
                </Submenu>
                <Submenu name="store">
                    <template slot="title"><i class="fa fa-building-o fa-fw" aria-hidden="true"></i>门店管理</template>
                    <MenuItem name="/admin/configStore">
                        <i class="fa fa-cog fa-fw" aria-hidden="true"></i>基础配置
                    </MenuItem>
                    <MenuItem name="/admin/roomType">
                        <i class="fa fa-cubes fa-fw" aria-hidden="true"></i>房间类型
                    </MenuItem>
                    <MenuItem name="/admin/roomList">
                        <i class="fa fa-cube fa-fw" aria-hidden="true"></i>房间列表
                    </MenuItem>
                    <MenuItem name="/admin/configAccount">
                        <i class="fa fa-user-plus fa-fw" aria-hidden="true"></i>账号配置
                    </MenuItem>
                    <MenuItem name="/admin/configChannel">
                        <i class="fa fa-handshake-o fa-fw" aria-hidden="true"></i>自定义渠道
                    </MenuItem>
                </Submenu>
                <Submenu name="member">
                    <template slot="title"><i class="fa fa-address-book-o fa-fw" aria-hidden="true"></i>会员管理</template>
                    <MenuItem name="/admin/memberRank"><i class="fa fa-users fa-fw" aria-hidden="true"></i>会员等级</MenuItem>
                    <MenuItem name="/admin/memberList"><i class="fa fa-user-plus fa-fw" aria-hidden="true"></i>会员列表</MenuItem>
                    <MenuItem name="/admin/memberBlack"><i class="fa fa-warning fa-fw" aria-hidden="true"></i>黑名单</MenuItem>
                </Submenu>
                <Submenu name="promotion">
                    <template slot="title"><i class="fa fa-fire fa-fw" aria-hidden="true"></i>活动管理</template>
                    <MenuItem name="/admin/discount"><i class="fa fa-rmb fa-fw" aria-hidden="true"></i>折扣</MenuItem>
                    <MenuItem name="/admin/cutdown"><i class="fa fa-money fa-fw" aria-hidden="true"></i>满减</MenuItem>
                    <MenuItem name="/admin/bargain"><i class="fa fa-bolt fa-fw" aria-hidden="true"></i>特价房</MenuItem>
                    <MenuItem name="/admin/coupon"><i class="fa fa-ticket fa-fw" aria-hidden="true"></i>优惠券</MenuItem>
                    <MenuItem name="/admin/gift"><i class="fa fa-gift fa-fw" aria-hidden="true"></i>抽奖</MenuItem>
                </Submenu>
                <Submenu name="person">
                    <template slot="title"><i class="fa fa-user-o fa-fw" aria-hidden="true"></i>用户中心</template>
                    <MenuItem name="/admin/personInfo">
                        <i class="fa fa-info fa-fw" aria-hidden="true"></i>个人资料
                    </MenuItem>
                    <MenuItem name="/admin/personPassword/0">
                        <i class="fa fa-lock fa-fw" aria-hidden="true"></i>修改密码
                    </MenuItem>
                    <MenuItem name="/admin/personNotice">
                        <i class="fa fa-bullhorn fa-fw" aria-hidden="true"></i>通知公告
                    </MenuItem>
                    <MenuItem name="/admin/personTips">
                        <i class="fa fa-send fa-fw" aria-hidden="true"></i>意见反馈
                    </MenuItem>
                </Submenu>
            </Menu>
        </div>
        <div class="layout-right">
            <transition name="slideRight">
                <router-view></router-view>
            </transition>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                userName: this.host.getUserName()
            };
        },
        methods:{
            turnUrl:function(name){
                this.$router.push(name);
            },
            logout (){
                this.host.post('loginOut').then(function(res){
                    if(res.isSuccess()){

                        this.$router.push('/login');
                    }else{
                        this.$Notice.info({
                            title: '错误提示',
                            desc: res.error()
                        })
                    }
                })
            }
        }
    }
</script>