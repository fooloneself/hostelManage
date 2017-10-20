<template>
<div>
    <Row>
        <Col span="24">
            <Button type="ghost" @click="goBack"><i class="fa fa-chevron-left icon-mr" aria-hidden="true"></i>返回</Button>
            <div class="mb"></div>
        </Col>
    </Row>
    <Row>
        <Col span="24">
            <h3>{{title}}</h3>
        </Col>
    </Row>
    <Row>
        <Col span="24">
            <p>{{content}}</p>
        </Col>
    </Row>
</div>
</template>

<script>
export default{
	data () {
		return {
			title:'',
			content:''
		}
	},
	mounted (){
	    var that=this;
	    this.host.post('mchNoticeRead',{id: this.$route.params.id}).then(function(res){
            if(res.isSuccess()){
                if(res.data()){
                    that.title=res.data().title;
                    that.content=res.data().content;
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