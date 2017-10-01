<template>
<div class="form">
	<Form label-position="right" :label-width="80">
		<FormItem label="渠道名称：">
			<Input></Input>
        </FormItem>
		<FormItem label="设置佣金：">
			<Input></Input>
        </FormItem>
		<FormItem label="渠道说明：">
            <Input type="textarea" :rows="5"></Input>
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
			
		}
	}
}
</script>