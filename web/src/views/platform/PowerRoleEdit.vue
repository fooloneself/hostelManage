<template>
<Row :gutter="24">
	<Col span="10">
		<Form label-position="right" :label-width="80">
			<FormItem label="角色名称：">
				<Input></Input>
	        </FormItem>
			<FormItem label="角色代码：">
				<Input></Input>
	        </FormItem>
	        <FormItem label="门店可选：">
				<RadioGroup v-model="formItem.radio">
	                <Radio label="0">是</Radio>
	                <Radio label="1">否</Radio>
	            </RadioGroup>
	        </FormItem>
			<FormItem label="菜单说明：">
	            <Input type="textarea" :rows="10"></Input>
	        </FormItem>
			<FormItem>
	            <Button type="primary">保存</Button>
                <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
	        </FormItem>
	    </Form>		
	</Col>
	<Col span="10" offset="1">
		选择权限：
		<Tree :data="baseData" show-checkbox></Tree>
	</Col>
</Row>
</template>

<script>
export default{
	data () {
		return {
			formItem:{
				radio:'0'
			},
			baseData: [{
                expand: true,
                title: 'parent 1',
                children: [{
                    title: 'parent 1-0',
                    expand: true,
                    disabled: true,
                    children: [{
                        title: 'leaf',
                        disableCheckbox: true
                    }, {
                        title: 'leaf',
                    }]
                }, {
                    title: 'parent 1-1',
                    expand: true,
                    checked: true,
                    children: [{
                        title: '<span style="color: red">leaf</span>',
                    }]
                }]
            }]
		}
	}
}
</script>