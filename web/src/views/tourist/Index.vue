<style scoped>
.container{
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    .banner-img{
        width: 100%;
        height: 100%;
        color: #ffffff;
        overflow: hidden;
        background: url('/src/images/bg.jpg') 50% 0% no-repeat;
        .banner-flyover{
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,.7);
        }
    }
}
.banner-title{
    letter-spacing: 3px;
    font-weight: 500;
    position: absolute;
    top: 300px;
    width: 100%;
    color: #ffffff;
    text-align: center;
    .info{
        font-size: 66px;
        margin-bottom: 16px;
    }
    .desc{
        font-size: 30px;
        margin-bottom: 16px;
    }
}
.banner-button{
    position: absolute;
    bottom: 100px;
    width: 100%;
    text-align: center;
    .btn{
        display: inline-block;
        width: 300px;
        height: 70px;
        line-height: 70px;
        font-size: 26px;
        font-weight: 500;
        color: #ffffff;
        background: #16A085;
    }
}
.content{
    width: 1000px;
    margin: 60px auto;
    .content-info{
        padding: 60px 0;
        .title{
            font-size: 28px;
            color: #495060;
            margin-bottom: 16px;
        }
        .desc{
            font-size: 16px;
            color: #80848f;
            line-height: 30px;
        }
        .fa{
            font-size: 120px;
        }
    }
}
.footer{
    height: 80px;
    line-height: 80px;
    border-top: 1px dotted #dddee1;
    .footer-info{
        width: 1000px;
        margin: 0 auto;
    }
}
</style>

<template>
<div>
    <div class="container">
        <div class="banner-img">
            <div class="banner-flyover"></div>
        </div>
        <div class="banner-title">
            <div class="desc"></div>
            <div class="info">回归纯粹 服务客户</div>
            <div class="desc">优客满客房管理系统</div>
        </div>
        <div class="banner-button">
            <router-link to="login" class="btn">免费使用</router-link>
        </div>
        <div class="content">
            <Row class="content-info" type="flex" justify="center" align="middle">
                <Col span="16">
                    <div class="title">支持房间房态便捷管理</div>
                    <div class="desc">支持房间房态便捷管理支持房间房态便捷管理支持房间房态便捷管理支持房间房态便捷管理支持房间房态便捷管理支持房间房态便捷管理支持房间房态便捷管理支持房间房态便捷管理</div>
                </Col>
                <Col span="8" class="tr">
                    <i class="fa fa-area-chart" aria-hidden="true"></i>
                </Col>
            </Row>
            <Row class="content-info">
                <Col span="8">
                    <i class="fa fa-bar-chart" aria-hidden="true"></i>
                </Col>
                <Col span="16">
                    <div class="title">支持门店多级会员管理</div>
                    <div class="desc">支持门店多级会员管理支持门店多级会员管理支持门店多级会员管理支持门店多级会员管理支持门店多级会员管理支持门店多级会员管理支持门店多级会员管理支持门店多级会员管理</div>
                </Col>
            </Row>
            <Row class="content-info">
                <Col span="16">
                    <div class="title">支持多方位的营销活动</div>
                    <div class="desc">支持多方位的营销活动支持多方位的营销活动支持多方位的营销活动支持多方位的营销活动支持多方位的营销活动支持多方位的营销活动支持多方位的营销活动支持多方位的营销活动</div>
                </Col>
                <Col span="8" class="tr">
                    <i class="fa fa-pie-chart" aria-hidden="true"></i>
                </Col>
            </Row>
            <Row class="content-info">
                <Col span="8">
                    <i class="fa fa-line-chart" aria-hidden="true"></i>
                </Col>
                <Col span="16">
                    <div class="title">支持门店多人分工合作</div>
                    <div class="desc">支持门店多人分工合作支持门店多人分工合作支持门店多人分工合作支持门店多人分工合作支持门店多人分工合作支持门店多人分工合作支持门店多人分工合作支持门店多人分工合作</div>
                </Col>
            </Row>
        </div>
        <div class="footer">
            <Row class="footer-info">
                <Col span="16">静静的为自己许下一个愿望，为此而努力，万一就实现了岂不是惊喜！</Col>
                <Col span="8" class="tr">Copyright@TwoBoys.</Col>
            </Row>
        </div>
    </div>
</div>
</template>

<script>
export default{
    data () {
        return {
        	value:0
        }
    }
}
</script>
