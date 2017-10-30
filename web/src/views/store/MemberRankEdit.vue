<template>
<Row>
	<Col span="12">
		<Form label-position="right" :label-width="80">
			<FormItem label="等级名称：">
				<Input></Input>
	        </FormItem>
			<FormItem label="消费区间：">
				<Row>
		            <Col span="11">
		                <Input></Input>
		            </Col>
		            <Col span="2" style="text-align: center">-</Col>
		            <Col span="11">
		                <Input></Input>
		            </Col>
		        </Row>
	        </FormItem>
			<FormItem label="积分区间：">
				<Row>
		            <Col span="11">
		                <Input></Input>
		            </Col>
		            <Col span="2" style="text-align: center">-</Col>
		            <Col span="11">
		                <Input></Input>
		            </Col>
		        </Row>
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
export default{}
</script>