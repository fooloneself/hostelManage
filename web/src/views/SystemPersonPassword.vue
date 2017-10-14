<template>
<Row>
	<Col span="12">
		<Form :model="formItem" label-position="right" :label-width="80">
			<FormItem label="旧密码：">
				<Input v-model="formItem.oldPassword"></Input>
	        </FormItem>
			<FormItem label="新密码：">
				<Input v-model="formItem.newPassword"></Input>
	        </FormItem>
			<FormItem label="重复密码：">
				<Input v-model="formItem.confirmPassword"></Input>
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
	                alert('密码重置成功');
	                that.form={};
	            }else{
	                alert(res.error());
	            }
	        })
	    }
	}
}
</script>