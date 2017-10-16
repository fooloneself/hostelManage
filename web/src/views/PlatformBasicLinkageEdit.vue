<template>
<Row>
	<Col span="24">
		<Button type="ghost" @click="goBack"><i class="fa fa-chevron-left icon-mr" aria-hidden="true"></i>返回</Button>
		<div class="mb"></div>
	</Col>
	<Col span="12">
		<Form :model="formItem" label-position="right" :label-width="80">
			<FormItem label="菜单名称：">
				<Input></Input>
	        </FormItem>
			<FormItem label="唯一代码：">
				<Input></Input>
	        </FormItem>
			<FormItem label="菜单说明：">
	            <Input type="textarea" :rows="10"></Input>
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
			formItem:{
				personIdType:'1',
				radio:'0'
			}
		}
	}
}
</script>