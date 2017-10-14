<template>
<Row>
	<Col span="12">
		<Form :model="formItem" label-position="right" :label-width="80">
			<FormItem label="登录账号：">{{userName}}</FormItem>
			<FormItem label="姓名：">
				<Input v-model='formItem.name'></Input>
	        </FormItem>
			<FormItem label="手机号：">
				<Input v-model='formItem.mobile'></Input>
	        </FormItem>
			<FormItem label="性别：">
				<RadioGroup v-model="formItem.sex">
	                <Radio v-for='item in sex' :label="item.key">{{item.value}}</Radio>
	            </RadioGroup>
	        </FormItem>
			<FormItem label="生日：">
				<DatePicker type="date" placeholder="选择日期" format="yyyy年MM月dd日" v-model="formItem.birthday" :value="formItem.birthday"></DatePicker>
	        </FormItem>
			<FormItem>
	            <Button type="primary" @click="submit">保存</Button>
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
			    name:'',
			    mobile:'',
				sex:'0',
				birthday:''
			},
			sex:[],
			userName:this.host.getUserName()
		}
	},
	mounted (){
	    var that=this;
	    this.host.post('sex').then(function(res){
            that.sex=res.data();
        })
        this.host.post('adminInfo').then(function(res){
            if(res.data())that.formItem=res.data();
        })
	},
    methods:{
        submit:function(){
            var birthday=this.formItem.birthday?Date.parse(new Date(this.formItem.birthday))/1000:0;
            var param={
                    name:this.formItem.name,
                    mobile:this.formItem.mobile,
                    sex:this.formItem.sex,
                    birthday: birthday
                };
            this.host.post('adminInfoModify',param).then(function(res){
                if(res.isSuccess()){
                    alert('成功修改信息');
                }else{
                    alert(res.error());
                }
            })
        }
    }
}
</script>