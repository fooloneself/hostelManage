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
			<FormItem label="分配角色：">
				<Select v-model="formItem.roleId" placeholder="请选择角色">
                    <Option v-for="role in roles" :value="role.id">{{role.name}}</Option>
                </Select>
	        </FormItem>
			<FormItem label="姓名：">
				<Input v-model="formItem.name"></Input>
	        </FormItem>
			<FormItem label="手机号：">
				<Input v-model="formItem.mobile"></Input>
	        </FormItem>
			<FormItem label="性别：">
				<RadioGroup v-model="formItem.sex">
	                <Radio v-for='item in sex' :label="item.key">{{item.value}}</Radio>
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
				roleId: '',
				name: '',
				sex: 0,
				birthday: '',
				mobile: ''
			},
			sex:[],
			roles:[]
		}
	},
	mounted(){
	    var that=this;
        this.host.post('sex').then(function(res){
            that.sex=res.data();
        })
        this.host.post('merchantAdminRoles').then(function(res){
            that.roles=res.data();
        })
	},
	methods:{
	    goBack (){
	        this.$router.go(-1);
	    },
	    submit (){
            var that=this;
            var birthday=this.formItem.birthday?Math.floor(Date.parse(new Date(this.formItem.birthday))/1000):0;
            var params={
                username: this.formItem.username,
                password: this.formItem.password,
                roleId: this.formItem.roleId,
                name: this.formItem.name,
                sex: this.formItem.sex,
                birthday: birthday,
                mobile: this.formItem.mobile
            }
            this.host.post('merchantAdminAdd',params).then(function(res){
                if(res.isSuccess()){
                    this.$router.push('/admin/configAccount');
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