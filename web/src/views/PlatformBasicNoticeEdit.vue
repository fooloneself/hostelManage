<template>
<Row>
	<Col span="24">
		<Button type="ghost" @click="goBack"><i class="fa fa-chevron-left icon-mr" aria-hidden="true"></i>返回</Button>
		<div class="mb"></div>
	</Col>
	<Col span="12">
		<Form :model="formItem" label-position="right" :label-width="60">
			<FormItem label="主题：">
				<Input></Input>
	        </FormItem>
			<FormItem label="内容：">
	            <Input type="textarea" :rows="20"></Input>
	        </FormItem>
			<FormItem>
	            <Button type="primary" @click="goBack">存草稿</Button>
	            <Button type="primary" style="margin-left: 8px" @click="goBack">保存并发送</Button>
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
				personIdType:'1'
			}
		}
	},
	methods:{
		goBack:function(){
			history.go(-1);
		}
	}
}
</script>