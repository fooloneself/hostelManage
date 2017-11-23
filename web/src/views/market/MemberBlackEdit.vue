<template>
<Row>
	<Col span="24">
        <Alert type="error" show-icon>
            <Icon type="alert-circled" slot="icon"></Icon>
            <template slot="desc">
                <span class="icon-ml">加入到黑名单的不良客户会被全网共享，当为黑名单上的客人办理订单时，系统会进行自动提醒。</span>
            </template>
        </Alert>
		<div class="mb"></div>
    </Col>
	<Col span="12">
		<Form v-model="black" label-position="right" :label-width="100">
			<FormItem label="人员姓名：">
				<Input v-model="black.name"></Input>
	        </FormItem>
			<FormItem label="证件号：">
		        <Input v-model="black.number">
					<Select v-model="black.numberType" slot="prepend" style="width: 80px">
			            <Option v-for="(numberType,nt) in numberTypes" :value="numberType.key">{{numberType.value}}</Option>
			        </Select>
		        </Input>
	        </FormItem>
			<FormItem label="手机号：">
				<Input v-model="black.mobile"></Input>
	        </FormItem>
			<FormItem label="说明：">
	            <Input v-model="black.mark" type="textarea" :rows="5"></Input>
	        </FormItem>
			<FormItem>
	            <Button @click="add" type="primary">保存</Button>
                <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
	        </FormItem>
	    </Form>
	</Col>
</Row>
</template>

<script>
export default{
    data(){
        return {
            numberTypes:[],
            black:{
                name:'',
                numberType:'',
                number:'',
                name: '',
                mark:''
            }
        };
    },
    mounted(){
        var that=this;
        if(this.$route.params.id>0){
            this.host.post('merchantMemberBlack',{id:this.$route.params.id}).then(function(res){
                if(res.isSuccess()){
                    that.black=res.data();
                    console.log(that.black);

                }else{
                    that.$Notice.info({
                        title:'提示',
                        desc:res.error()
                    })
                }
            })
        }
        this.host.post('merchantNumberTypes').then(function(res){
            if(res.isSuccess()){
                that.numberTypes=res.data();
            }else{
                that.$Notice.info({
                    title:'提示',
                    desc:res.error()
                })
            }
        })
    },
	methods:{
	    goBack (){
	        this.$router.go(-1);
	    },
	    add(){
	        var that=this;
	        this.black.id=this.$route.params.id;
            this.host.post('merchantMemberBlackAdd',this.black).then(function(res){
                if(res.isSuccess()){
                    that.goBack();
                }else{
                    that.$Notice.info({
                        title:'提示',
                        desc:res.error()
                    })
                }
            })
	    }
	}
}
</script>