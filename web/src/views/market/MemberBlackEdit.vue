<template>
<Row>
	<Col span="24">
        <Alert type="error" show-icon>
            <Icon type="alert-circled" slot="icon"></Icon>
            <template slot="desc">
                <span class="icon-ml">加入到黑名单的不良客户会被全网共享，当为黑名单上的客人办理订单时，系统会进行自动提醒。</span>
            </template>
        </Alert>
		<div class="mb"></div>
    </Col>
	<Col span="12">
		<Form label-position="right" :label-width="100">
			<FormItem label="人员姓名：">
				<Input></Input>
	        </FormItem>
			<FormItem label="证件号：">
		        <Input>
					<Select slot="prepend" style="width: 80px">
			            <Option>身份证</Option>
			            <Option>护照</Option>
			        </Select>
		        </Input>
	        </FormItem>
			<FormItem label="手机号：">
				<Input></Input>
	        </FormItem>
			<FormItem label="等级说明：">
	            <Input type="textarea" :rows="5"></Input>
	        </FormItem>
			<FormItem>
	            <Button type="primary">保存</Button>
                <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
	        </FormItem>
	    </Form>
	</Col>
</Row>
</template>

<script>
export default{
	methods:{
	    goBack (){
	        this.$router.go(-1);
	    }
	}
}
</script>