<style scoped>
.number{
    font-weight: bolder;
    color: #ffffff;
    margin-right: 4px;
}
</style>

<template>
<div style="margin-top: -24px;">
    <Tabs value="info">
        <TabPane label="短信数据" name="info">
            <Alert show-icon>
                <Icon type="ios-lightbulb-outline" slot="icon"></Icon>
                <template slot="desc">
                    1、制定活动以后可以发送短信通知给相应会员，提高活动效率；</br>
                    2、会员生日；</br>
                    3、会员入住、退房提醒服务；</br>
                    <Button type="warning" size="large" class="mt">
                        <i class="fa fa-envelope-o icon-mr fa-lg" aria-hidden="true"></i>
                        点击购买（余量：<span class="number">1688</span>条）
                    </Button>
                </template>
            </Alert>
            <div class="mb"></div>
			<Table :columns="columns" :data="data" stripe></Table>
			<div class="mb"></div>
			<Page :total="totalCount" :current-page="filter.page" :page-size="filter.pageSize" show-total></Page>
        </TabPane>
        <TabPane label="模板配置" name="template">
        </TabPane>
    </Tabs>
</div>
</template>

<script>
export default{
	data () {
        return {
            columns: [
                {
                    title: '序号',
                    width: 60,
                    type: 'index'
                },
                {
                    title: '手机号',
                    width: 120,
                    key: 'mobile'
                },
                {
                    title: '会员等级',
                    width: 120,
                    key: 'rank'
                },
                {
                    title: '人员姓名',
                    width: 120,
                    key: 'name'
                },
                {
                    title: '发送状态',
                    width: 100,
                    key: 'consumption_amount'
                },
                {
                    title: '发送时间',
                    width: 160,
                    key: 'register_date'
                },
                {
                    title: '发送内容',
                    key: 'integral'
                }
            ],
            data: [],
            totalCount: 0,
            filter:{
                page:1,
                pageSize:10
            }
        }
    }
}
</script>