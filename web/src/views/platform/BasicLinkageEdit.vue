<template>
<Row>
	<Col span="12">
		<Form v-model="formItem" label-position="right" :label-width="80">
			<FormItem label="菜单名称：">
				<Input v-model="formItem.label"></Input>
	        </FormItem>
			<FormItem label="唯一代码：">
				<Input v-model="formItem.code"></Input>
	        </FormItem>
			<FormItem label="菜单说明：">
	            <Input v-model="formItem.introduce" type="textarea" :rows="10"></Input>
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
				id:this.$route.params.id,
				code: '',
				label: '',
				introduce: ''
			}
		}
	},
	mounted (){
	    if(this.$route.params.id>0){
	        var that=this;
	        this.host.post('linkageMenuView',{id: this.$route.params.id}).then(function(res){
	            if(res.isSuccess()){
	                if(res.data()){
                        that.formItem.code=res.data().code;
                        that.formItem.label=res.data().label;
                        that.formItem.introduce=res.data().introduce;
	                }
	            }else{
	                that.$Notice.info({
	                    title: '提示',
	                    desc: res.error()
	                })
	            }
	        })
	    }
	},
	methods:{
	    goBack(){
	        this.$router.go(-1);
	    },
	    submit (){
	        this.host.post('linkageMenuRecord',this.formItem).then(function(res){
	            if(res.isSuccess()){
	                this.$router.go(-1);
	            }else{
	                this.$Notice.info({
	                    title:'提示',
	                    desc: res.error()
	                })
	            }
	        })
	    }
	}
}
</script>