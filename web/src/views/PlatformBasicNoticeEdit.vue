<template>
<Row>
	<Col span="24">
		<Button type="ghost" @click="goBack"><i class="fa fa-chevron-left icon-mr" aria-hidden="true"></i>返回</Button>
		<div class="mb"></div>
	</Col>
	<Col span="12">
		<Form v-model="formItem" label-position="right" :label-width="60">
			<FormItem label="主题：">
				<Input v-model="formItem.title"></Input>
	        </FormItem>
			<FormItem label="内容：">
	            <Input v-model="formItem.content" type="textarea" :rows="20"></Input>
	        </FormItem>
			<FormItem>
	            <Button type="primary" @click="draft">存草稿</Button>
	            <Button type="primary" @click="public" style="margin-left: 8px">保存并发送</Button>
	        </FormItem>
	    </Form>
	</Col>
</Row>
</template>

<script>
export default{
	data () {
		return {
			formItem:{
				id: this.$route.params.id,
				title: '',
				content: ''
			}
		}
	},
	mounted (){
	    if(this.$route.params.id>0){
	        var that=this;
	        this.host.post('platformNoticeView',{id: this.$route.params.id}).then(function(res){
	            if(res.isSuccess()){
	                if(res.data()){
	                    that.formItem.title=res.data().title;
	                    that.formItem.content=res.data().content;
	                }
	            }else{
	                that.$Notice.info({
	                    title:'提示',
	                    desc: res.error()
	                })
	            }
	        })
	    }
	},
	methods:{
		goBack:function(){
			history.go(-1);
		},
		draft (){
		    var that=this;
		    var param={
		        id: this.formItem.id,
		        title: this.formItem.title,
		        content:this.formItem.content,
		        status:1
		    };
		    this.host.post('platformNoticeEdit',param).then(function(res){
		        if(res.isSuccess()){
		            that.goBack();
		        }else{
		            this.$Notice.info({
		                title: '提示',
		                desc: res.error()
		            })
		        }
		    })
		},
		public(){
            var that=this;
		    var param={
		        id: this.formItem.id,
		        title: this.formItem.title,
		        content:this.formItem.content,
		        status:2
		    };
		    this.host.post('platformNoticeEdit',param).then(function(res){
		        if(res.isSuccess()){
		            that.goBack();
		        }else{
		            this.$Notice.info({
		                title: '提示',
		                desc: res.error()
		            })
		        }
		    })
		}
	}
}
</script>