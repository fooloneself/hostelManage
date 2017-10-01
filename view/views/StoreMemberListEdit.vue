<template>
<div class="form">
	<Form :model="formItem" label-position="right" :label-width="80">
		<FormItem label="姓名：">
			<Input></Input>
        </FormItem>
		<FormItem label="手机号：">
			<Input></Input>
        </FormItem>
		<FormItem label="证件号：">
			<Input>
				<Select v-model="formItem.personIdType" slot="prepend" style="width: 80px">
		            <Option value="1">身份证</Option>
		            <Option value="2">军官证</Option>
		            <Option value="3">护照</Option>
		        </Select>
	        </Input>
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
		<FormItem label="微信号：">
			<Input></Input>
        </FormItem>
		<FormItem label="备注：">
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
			formItem:{
				personIdType:'1',
				radio:'0'
			}
		}
	}
}
</script>