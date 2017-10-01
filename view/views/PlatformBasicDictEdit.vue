<template>
<div class="form">
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
            <Button type="primary">保存</Button>
            <Button type="ghost" style="margin-left: 8px">返回</Button>
        </FormItem>
    </Form>
</div>
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