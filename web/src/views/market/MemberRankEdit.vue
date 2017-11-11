<template>
<Row>
	<Col span="12">
		<Form label-position="right" :label-width="100">
			<FormItem label="等级名称：">
				<Input></Input>
	        </FormItem>
			<FormItem label="消费金额满：">
		        <Input><span slot="prepend">￥</span></Input>
	        </FormItem>
			<FormItem label="积分满：">
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