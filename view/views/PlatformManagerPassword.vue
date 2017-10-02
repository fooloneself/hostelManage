<template>
<Row>
	<Col span="12">
		<Form label-position="right" :label-width="80">
			<FormItem label="旧密码：">
				<Input></Input>
	        </FormItem>
			<FormItem label="新密码：">
				<Input></Input>
	        </FormItem>
			<FormItem label="重复密码：">
				<Input></Input>
	        </FormItem>
			<FormItem>
	            <Button type="primary">保存</Button>
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