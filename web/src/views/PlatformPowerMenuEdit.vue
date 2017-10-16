<template>
<Row>
	<Col span="24">
		<Button type="ghost" @click="goBack"><i class="fa fa-chevron-left icon-mr" aria-hidden="true"></i>返回</Button>
		<div class="mb"></div>
	</Col>
	<Col span="12">
		<Form :model="formItem" label-position="right" :label-width="80">
			<FormItem label="父级菜单：">无</FormItem>
			<FormItem label="菜单类型：">
				<Select v-model="formItem.personIdType">
		            <Option value="1">菜单</Option>
		            <Option value="2">菜单组</Option>
		            <Option value="3">按钮</Option>
		        </Select>
	    	</FormItem>
			<FormItem label="菜单名称：">
				<Input></Input>
	        </FormItem>
			<FormItem label="是否收费：">
				<RadioGroup v-model="formItem.radio">
	                <Radio label="0">否</Radio>
	                <Radio label="1">是</Radio>
	            </RadioGroup>
	        </FormItem>
			<FormItem label="菜单价格：">
				<Input></Input>
	        </FormItem>
			<FormItem label="菜单排序：">
				<Input></Input>
	        </FormItem>
			<FormItem label="访问URL：">
				<Input></Input>
	        </FormItem>
			<FormItem label="菜单说明：">
	            <Input type="textarea" :rows="5"></Input>
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