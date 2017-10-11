<template>
	<Row>
        <Col span="5">
            <Menu theme="light" active-name="1" @on-select="turnUrl">
                <MenuGroup title="订单管理">
                    <MenuItem name="orderIn">
                        <Icon type="document-text"></Icon>
                        今日新办
                    </MenuItem>
                    <MenuItem name="orderOut">
                        <Icon type="chatbubbles"></Icon>
                        即将离店
                    </MenuItem>
                    <MenuItem name="orderAll">
                        <Icon type="chatbubbles"></Icon>
                        全部订单
                    </MenuItem>
                    <MenuItem name="orderError">
                        <Icon type="chatbubbles"></Icon>
                        异常订单
                    </MenuItem>
                </MenuGroup>
                <MenuGroup title="会员管理">
                    <MenuItem name="memberRank">
                        <Icon type="document-text"></Icon>
                        会员等级
                    </MenuItem>
                    <MenuItem name="memberList">
                        <Icon type="chatbubbles"></Icon>
                        会员列表
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