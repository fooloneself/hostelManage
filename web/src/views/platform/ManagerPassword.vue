<template>
<Row>
	<Col span="24">
		<Alert show-icon>
	        <Icon type="ios-lightbulb-outline" slot="icon"></Icon>
	        <template slot="desc">
				小提示：为了您的数据安全，密码必须是8位及以上，包含数字、字母和特殊符号任意两种。
	        </template>
	    </Alert>
		<div class="mb"></div>
	</Col>
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
		        adminId: this.$route.params.adminId,
		        oldPassword: "",
		        newPassword: "",
		        confirmPassword: ""
		    }
		}
	},
	methods:{
	    submit: function(){
	        var that=this;
	        this.host.post('resetPlatformPwd',this.formItem).then(function(res){
	            if(res.isSuccess()){
	                that.$Notice.info({
                        title: '提示',
                        desc: '密码重置成功'
                    });
	                this.$router.go(-1);
	            }else{
	                that.$Notice.info({
                        title: '提示',
                        desc: res.error()
                    });
	            }
	        })
	    }
	}
}
</script>