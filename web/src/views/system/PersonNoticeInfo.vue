<style scoped>
    h3{
        font-size: 20px;
        margin-bottom: 24px;
        color: #464c5b;
    }
    h4{
        font-size: 14px;
        margin-bottom: 24px;
        color: #9ea7b4;
    }
    p{
        line-height: 28px;
        font-size: 14px;
        color: #657180;
        letter-spacing: 0.03em;
    }
</style>

<template>
<Row>
    <Col span="24">
        <Button type="ghost" @click="goBack"><i class="fa fa-chevron-left icon-mr" aria-hidden="true"></i>返回列表</Button>
    </Col>
    <Col span="22" offset="1">
        <div class="mb"></div>
        <h3>{{title}}</h3>
        <h4><i class="fa fa-calendar icon-mr" aria-hidden="true"></i>{{time}}</h4>
        <p>{{content}}</p>
    </Col>
</Row>
</template>

<script>
export default{
	data () {
		return {
			title:'',
			content:'',
            time:''
		}
	},
	mounted (){
	    var that=this;
	    this.host.post('mchNoticeRead',{id: this.$route.params.id}).then(function(res){
            if(res.isSuccess()){
                if(res.data()){
                    that.title=res.data().title;
                    that.content=res.data().content;
                    that.time=res.data().publicDate;
                }
            }else{
                that.$Notice.info({
                    title :'提示',
                    desc: res.error()
                })
            }
	    })
	},
	methods:{
		goBack:function(){
			history.go(-1);
		}
	}
}
</script>