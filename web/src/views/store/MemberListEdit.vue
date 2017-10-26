<template>
<Row>
	<Col span="12">
		<Form v-model="formItem" label-position="right" :label-width="80">
			<FormItem label="姓名：">
				<Input v-model="formItem.name"></Input>
	        </FormItem>
			<FormItem label="手机号：">
				<Input v-model="formItem.mobile"></Input>
	        </FormItem>
			<FormItem label="证件号：">
				<Input v-model="formItem.number">
					<Select v-model="formItem.numberType" :value="formItem.numberType" slot="prepend" style="width: 80px">
			            <Option v-for="type in numberType" :value="type.key">{{type.value}}</Option>
			        </Select>
		        </Input>
	        </FormItem>
			<FormItem label="性别：">
				<RadioGroup v-model="formItem.sex">
	                <Radio v-for="item in sex" :label="item.key">{{item.value}}</Radio>
	            </RadioGroup>
	        </FormItem>
			<FormItem label="生日：">
				<DatePicker v-model="formItem.birthday" :value="formItem.birthday" type="date" placeholder="选择日期"></DatePicker>
	        </FormItem>
			<FormItem label="微信号：">
				<Input v-model="formItem.wxAccount"></Input>
	        </FormItem>
			<FormItem label="备注：">
	            <Input v-model="formItem.mark" type="textarea" :rows="5"></Input>
	        </FormItem>
			<FormItem>
	            <Button @click="submit" type="primary">保存</Button>
                <Button type="ghost" @click="goBack" class="icon-ml">返回</Button>
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
				mobile:'',
				numberType: 0,
				number: '',
				sex: 0,
				birthday: '',
				wxAccount: '',
				mark: ''
			},
			sex:[],
			numberType:[]
		}
	},
	mounted (){
        var that=this;
        this.host.post('merchantMemberEditInfo',{id: this.$route.params.id}).then(function(res){
            if(res.isSuccess()){
                that.sex=res.data().sex;
                that.numberType=res.data().numberType;
                var member=res.data().member
                if(member){
                    that.formItem.name=member.name;
                    that.formItem.mobile=member.mobile;
                    that.formItem.numberType=member.numberType;
                    that.formItem.number=member.number;
                    that.formItem.sex=member.sex;
                    that.formItem.birthday=member.birthday;
                    that.formItem.wxAccount=member.wxAccount;
                    that.formItem.mark=member.mark;
                }
            }else{
                that.$Notice.info({
                    title: '提示',
                    desc: res.error()
                })
            }
        })
	},
	methods:{
	    submit (){
	        var birthday=0;
	        if(this.formItem.birthday){
	            birthday=Math.floor(Date.parse(new Date(this.formItem.birthday))/1000);
	        }
            var params={
                id: this.formItem.id,
                name: this.formItem.name,
                mobile: this.formItem.mobile,
                numberType: this.formItem.numberType,
                number: this.formItem.number,
                sex: this.formItem.sex,
                birthday: birthday,
                wxAccount: this.formItem.wxAccount,
                mark: this.formItem.mark
            };
            this.host.post('merchantMemberEdit',params).then(function(res){
                if(res.isSuccess()){
                    this.$router.push('/admin/memberList');
                }else{
                    this.$Notice.info({
                        title: '提示',
                        desc: res.error()
                    })
                }
            })
	    },
	    goBack (){
	        this.$router.go(-1);
	    }
	}
}
</script>