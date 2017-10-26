<template>
<Row>
	<Col span="12">
		<Form :model="formItem" label-position="right" :label-width="80">
			<FormItem label="旧密码：">
				<Input v-model="formItem.oldPassword" type="password"></Input>
	        </FormItem>
			<FormItem label="新密码：">
				<Input v-model="formItem.newPassword" type="password"></Input>
	        </FormItem>
			<FormItem label="重复密码：">
				<Input v-model="formItem.confirmPassword" type="password"></Input>
	        </FormItem>
			<FormItem>
	            <Button @click="submit" type="primary">保存</Button>
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
		        oldPassword: "",
		        newPassword: "",
		        confirmPassword: ""
		    }
		}
	},
	methods:{
	    submit: function(){
	        var that=this;
	        this.host.post('resetMchPwd',this.formItem).then(function(res){
	            if(res.isSuccess()){
	                this.$Notice.info({
                        title: '提示',
                        desc: '重置密码成功'
                    });
	                that.form={};
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