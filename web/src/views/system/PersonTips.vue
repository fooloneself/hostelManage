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
		<Col span="12">
			<Form :model="formItem" label-position="right" :label-width="80">
				<FormItem label="建议&意见：">
		            <Input v-model="formItem.content" type="textarea" :rows="10"></Input>
		        </FormItem>
				<FormItem>
		            <Button type="primary" @click="submit">提交</Button>
		        </FormItem>
		    </Form>
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