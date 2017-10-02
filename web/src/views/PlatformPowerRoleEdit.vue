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
			<FormItem label="有效期限：">
				<DatePicker type="daterange"></DatePicker>
	        </FormItem>
			<FormItem label="菜单说明：">
	            <Input type="textarea" :rows="10"></Input>
	        </FormItem>
			<FormItem>
	            <Button type="primary">保存</Button>
	            <Button type="ghost" style="margin-left: 8px">返回</Button>
	        </FormItem>
	    </Form>		
	</Col>
	<Col span="10">
		选择权限：
		<Tree :data="baseData" show-checkbox></Tree>
	</Col>
</Row>
</template>

<script>
export default{
	data () {
		return {
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