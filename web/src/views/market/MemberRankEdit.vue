<template>
<Row>
	<Col span="12">
		<Form v-model="rank" label-position="right" :label-width="100">
			<FormItem label="等级名称：">
				<Input v-model="rank.name"></Input>
	        </FormItem>
			<FormItem label="消费金额满：">
		        <Input v-model="rank.minConsumptionAmount"></Input>
	        </FormItem>
			<!-- <FormItem label="积分满：">
				<Input v-model="rank.minIntegral"></Input>
	        </FormItem> -->
			<FormItem label="等级说明：">
	            <Input v-model="rank.mark" type="textarea" :rows="5"></Input>
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
    data(){
        return {
            rank:{
                id:this.$route.params.id,
                name: '',
                minConsumptionAmount:'',
                minIntegral: 0,
                mark:''
            }
        };
    },
    mounted(){
        var that=this;
        if(this.$route.params.id>0){
            this.host.post('merchantMemberRank',{id: this.$route.params.id}).then(function(res){
                if(res.isSuccess()){
                    that.rank=res.data();
                }else{
                    this.$Notice.info({
                        title:'错误提示',
                        desc: res.error()
                    })
                }
            })
        }
    },
	methods:{
	    goBack (){
	        this.$router.go(-1);
	    },
	    submit(){
	        this.rank.id=this.$route.params.id;
	        this.host.post('merchantMemberRankEdit',this.rank).then(function(res){
	            if(res.isSuccess()){
	                this.$router.push('/admin/memberRank');
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