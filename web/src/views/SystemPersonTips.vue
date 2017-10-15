<template>
	<Row>
		<Col span="12">
			<Form :model="formItem" label-position="right" :label-width="80">
				<FormItem label="建议&意见：">
		            <Input v-model="formItem.content" type="textarea" :rows="10"></Input>
		        </FormItem>
				<FormItem>
		            <Button type="primary" @click="submit">保存</Button>
		            <Button type="ghost" style="margin-left: 8px">返回</Button>
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
                    alert("意见反馈成功，我们会尽快处理！");
                }else{
                    alert(res.error());
                }
            })
	    }
	}
}
</script>