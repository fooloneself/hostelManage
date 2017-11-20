<template>
<Row>
    <Col span="4">
        <Menu theme="light" active-name="/admin/orderIn" @on-select="turnUrl" style="width: auto">
            <MenuGroup name="order" title="订单管理">
                <MenuItem name="/admin/orderIn">
                    <i class="fa fa-calendar-plus-o fa-fw" aria-hidden="true"></i>今日到店
                </MenuItem>
                <MenuItem name="/admin/orderOut">
                    <i class="fa fa-calendar-minus-o fa-fw" aria-hidden="true"></i>今日离店
                </MenuItem>
                <MenuItem name="/admin/orderAll">
                    <i class="fa fa-calendar-o fa-fw" aria-hidden="true"></i>全部订单
                </MenuItem>
                <MenuItem name="/admin/orderError">
                    <i class="fa fa-calendar-times-o fa-fw" aria-hidden="true"></i>异常订单
                </MenuItem>
            </MenuGroup>
            <MenuGroup name="ota" title="渠道直连">
                <MenuItem name="/admin/otaXiecheng">
                    <i class="fa fa-link fa-fw" aria-hidden="true"></i>携程网
                </MenuItem>
                <MenuItem name="/admin/otaQunaer">
                    <i class="fa fa-link fa-fw" aria-hidden="true"></i>去哪儿网
                </MenuItem>
                <MenuItem name="/admin/otaYilong">
                    <i class="fa fa-link fa-fw" aria-hidden="true"></i>艺龙网
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