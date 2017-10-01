<template>
<div class="form">
	<Form label-position="right" :label-width="100">
		<FormItem label="房屋类型：">
			<Input></Input>
        </FormItem>
		<FormItem label="默认价格：">
			<Input></Input>
        </FormItem>
        <FormItem label="允许钟点房：">
            <RadioGroup >
                <Radio label="0">是</Radio>
                <Radio label="1">否</Radio>
            </RadioGroup>
        </FormItem>
		<FormItem label="房型说明：">
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