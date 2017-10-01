<template>
	<Row>
		<Col span="12">
			<Form label-position="right" :label-width="80">
				<FormItem label="建议&意见：">
		            <Input type="textarea" :rows="10"></Input>
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