<style scoped>
.goods{
    margin-bottom: 24px;
    .goods-img{
        width: 100%;
        height: auto;
    }
    .goods-info{
        height: 40px;
        line-height: 20px;
        overflow: hidden;
        font-size: 12px;
    }
    .goods-price{
        margin-top: 8px;
        color: #D35400;
        font-weight: bold;
    }
}
</style>

<template>
<Row :gutter="24">
	<Col span="4" v-for="i in 36">
		<Card class="goods">
            <img src="/src/images/timg1.jpg" alt="" class="goods-img">
            <p class="goods-info">2017秋冬季新款加绒加厚连帽衫套头卫衣女学生小清新修身显瘦上衣</p>
            <p class="goods-price">￥79.90</p>
        </Card>
    </Col>
    <Col span="24">
        <Page :total="totalCount" :page-size="pageSize" :current="page" show-total></Page>
    </Col>
</Row>
</template>

<script>
export default{
    data() {
        return {
            totalCount: 100,
            page: 1,
            pageSize: 10
        }
    }
}
</script>