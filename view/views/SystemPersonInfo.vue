<template>
<Row>
	<Col span="12">
		<Form :model="formItem" label-position="right" :label-width="80">
			<FormItem label="登录账号：">Admin Dun</FormItem>
			<FormItem label="姓名：">
				<Input></Input>
	        </FormItem>
			<FormItem label="手机号：">
				<Input></Input>
	        </FormItem>
			<FormItem label="性别：">
				<RadioGroup v-model="formItem.radio">
	                <Radio label="0">男</Radio>
	                <Radio label="1">女</Radio>
	            </RadioGroup>
	        </FormItem>
			<FormItem label="生日：">
				<DatePicker type="date" placeholder="选择日期"></DatePicker>
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
				radio:'0'
			}
		}
	}
}
</script>