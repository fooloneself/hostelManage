<style scoped>
.rent{
    margin-bottom: 24px;
    a,.price{
        color: #D35400;
        font-weight: bold;
    }
    .rent-info p{
        height: 100px;
        overflow: hidden;
    }
    img{
        width: 100%;
        height: auto;
    }
    h4{
        line-height: 26px;
    }
    .em{
        margin-right: 8px;
    }
}
</style>

<template>
<Row :gutter="24">
	<Col span="8">
		<Card class="rent">
            <p slot="title">Mini 台式主机</p>
            <p slot="extra">
                <span class="price">最低2元/天</span>
            </p>
            <div class="rent-info">
                <img src="/src/images/timg1.jpg" alt="">
                <h4>主机简介</h4>
                <p>不能耍游戏，你个瓜娃子！送键鼠套装！</p>
            </div>
            <div class="tr mt">
                <Button size="large" type="error" long>租<span class="em"></span>借</Button>
            </div>
        </Card>
    </Col>
    <Col span="8">
        <Card class="rent">
            <p slot="title">AMD 性能主机</p>
            <p slot="extra">
                <span class="price">最低3元/天</span>
            </p>
            <div class="rent-info">
                <img src="/src/images/timg1.jpg" alt="">
                <h4>主机简介</h4>
                <p>耍游戏耍的飞起！送键鼠套装！</p>
            </div>
            <div class="tr mt">
                <Button size="large" type="error" long>租<span class="em"></span>借</Button>
            </div>
        </Card>
    </Col>
    <Col span="8">
        <Card class="rent">
            <p slot="title">Intel 稳定主机</p>
            <p slot="extra">
                <span class="price">最低3元/天</span>
            </p>
            <div class="rent-info">
                <img src="/src/images/timg1.jpg" alt="">
                <h4>主机简介</h4>
                <p>电费很贵，这个是最节约电费的！送键鼠套装！</p>
            </div>
            <div class="tr mt">
                <Button size="large" type="error" long>租<span class="em"></span>借</Button>
            </div>
        </Card>
    </Col>
</Row>
</template>

<script>
export default{

}
</script>