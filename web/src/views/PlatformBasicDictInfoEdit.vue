<template>
<Row>
	<Col span="12">
		<Form label-position="right" :label-width="80">
			<FormItem label="字典名称：">字典名称</FormItem>
			<FormItem label="数据项：">
				<Input></Input>
	        </FormItem>
			<FormItem label="数据值：">
				<Input></Input>
	        </FormItem>
			<FormItem label="排序：">
				<Input></Input>
	        </FormItem>
			<FormItem>
	            <Button type="primary">保存</Button>
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
		}
	}
}
</script>