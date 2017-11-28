<style scoped>
.commit{
    padding: 16px;
    border: 1px solid #ccf5e0;
    background: #e6faf0;
    border-radius: 6px;
    color: #657180;
    margin-top: 8px;
    line-height: 22px;
}
</style>

<template>
	<Row>
		<Col span="24">
			<Alert show-icon>
		        <Icon type="ios-lightbulb-outline" slot="icon"></Icon>
		        <template slot="desc">
					小提示：对系统有什么建议和想法可以通过下面的文本框反馈给我们，感谢您的反馈！
		        </template>
		    </Alert>
		    <div class="mb"></div>
		</Col>
		<Col span="24">
			<Form :model="formItem" label-position="right" :label-width="80">
				<FormItem label="建议&意见：">
		            <Input v-model="formItem.content" type="textarea" :rows="5"></Input>
		        </FormItem>
				<FormItem>
		            <Button type="primary" @click="submit">提交</Button>
		        </FormItem>
		    </Form>
		</Col>
		<Col span="24">
			<template v-for="i in 10">
		        <Card>
		        	<h4 slot="title">系统管理员</h4>
		        	非常感谢您的建议
		            <div class="commit">
		                怎么反应速度这么慢，交互也差，搞什么东东
		            </div>
		        </Card>
		        <div class="mb"></div>
		    </template>
		</Col>
	</Row>
</template>

<script>
export default{
	data () {
		return {
		    formItem: {
		        content: ""
		    }
		}
	},
	methods:{
	    submit: function(){
            this.host.post("tips",{feedback: this.formItem.content}).then(function(res){
                if(res.isSuccess()){
                    this.$Notice.info({
                        title: '提示',
                        desc: '意见反馈成功，我们会尽快处理！'
                    });
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