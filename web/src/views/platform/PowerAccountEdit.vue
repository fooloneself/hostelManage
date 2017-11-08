<template>
<Row>
	<Col span="12">
		<Form v-model="formItem" label-position="right" :label-width="80">
			<FormItem label="登录账号：">
				<Input v-model="formItem.username"></Input>
	        </FormItem>
			<FormItem label="密码：">
				<Input v-model="formItem.password" type="password"></Input>
	        </FormItem>
			<FormItem label="有效期限：">
				<DatePicker v-model="formItem.expire" type="date"></DatePicker>
	        </FormItem>
			<FormItem label="姓名：">
				<Input v-model="formItem.name"></Input>
	        </FormItem>
			<FormItem label="手机号：">
				<Input v-model="formItem.mobile"></Input>
	        </FormItem>
			<FormItem label="性别：">
				<RadioGroup v-model="formItem.sex">
	                <Radio v-for="item in sex" :label="item.key">{{item.value}}</Radio>
	            </RadioGroup>
	        </FormItem>
			<FormItem label="生日：">
				<DatePicker v-model="formItem.birthday" type="date" placeholder="选择日期"></DatePicker>
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
				username: '',
				password: '',
				expire: '',
				name: '',
				mobile: '',
				sex: 0,
				birthday: ''
			},
			sex: []
		}
	},
	mounted (){
	    var that=this;
	    this.host.post('sex').then(function(res){
	        if(res.data())that.sex=res.data();
	    })
	},
	methods:{
	    goBack (){
	        this.$router.go(-1);
	    },
	    getTimestamp(dateStr){
            if(dateStr){
                return Math.floor(Date.parse(new Date(dateStr))/1000);
            }else{
                return 0;
            }
	    },
	    submit (){
	        var params={
                username: this.formItem.username,
                password: this.formItem.password,
                expire: this.getTimestamp(this.formItem.expire),
                name: this.formItem.name,
                mobile: this.formItem.mobile,
                sex: this.formItem.sex,
                birthday: this.getTimestamp(this.formItem.birthday)
            };
	        this.host.post('platformAdminAdd',params).then(function(res){
	            if(res.isSuccess()){
	                this.$router.push('/admin/powerAccount');
	            }else{
                    this.$Notice.info({
                        title: '错误提示',
                        desc: res.error()
                    })
	            }
	        })
	    }
	}
}
</script>