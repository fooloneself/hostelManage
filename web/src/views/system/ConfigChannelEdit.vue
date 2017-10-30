<template>
<Row>
	<Col span="12">
		<Form v-model="formItem" label-position="right" :label-width="80">
			<FormItem label="渠道名称：">
				<Input v-model="formItem.name"></Input>
	        </FormItem>
			<FormItem label="设置佣金：">
				<Input v-model="formItem.commission"></Input>
	        </FormItem>
			<FormItem label="渠道说明：">
	            <Input v-model="formItem.introduce" type="textarea" :rows="5"></Input>
	        </FormItem>
			<FormItem>
	            <Button @click="submit" type="primary">保存</Button>
                <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
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
			    name: '',
			    commission: null,
			    introduce: ''
			}
		}
	},
	mounted(){
	    if(this.$route.params.id>0){
	        var that=this;
	        this.host.post('channelView',{id: this.$route.params.id}).then(function(res){
	            if(res.isSuccess()){
	                if(res.data()){
	                    that.formItem.name=res.data().name;
	                    that.formItem.commission=res.data().commission;
	                    that.formItem.introduce=res.data().introduce;
	                }
	            }else{
	                that.$Notice.info({
                        title: '提示',
                        desc: res.error()
                    });
	            }
	        })
	    }
	},
	methods:{
	    goBack (){
	        this.$router.go(-1);
	    },
	    submit (){
	        var that=this;
            this.host.post('channelRecord',this.formItem).then(function(res){
                if(res.isSuccess()){
                    that.goBack();
                }else{
                    this.$Notice.info({
                        title: '提示',
                        desc: res.error()
                    });
                }
            })
	    }
	}
}
</script>