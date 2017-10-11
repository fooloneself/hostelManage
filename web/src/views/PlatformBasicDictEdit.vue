<template>
<Row>
	<Col span="12">
		<Form :model="formItem" label-position="right" :label-width="80">
			<FormItem label="字典名称：">
				<Input></Input>
	        </FormItem>
			<FormItem label="唯一代码：">
				<Input></Input>
	        </FormItem>
			<FormItem label="菜单说明：">
	            <Input type="textarea" :rows="10"></Input>
	        </FormItem>
			<FormItem>
	            <Button type="primary" @click="goBack">保存</Button>
	            <Button type="ghost" style="margin-left: 8px" @click="goBack">返回</Button>
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