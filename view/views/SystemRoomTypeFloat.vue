<template>
	<Tabs value="week">
        <TabPane label="周价格浮动" name="week">
	        <Row>
	        	<Col span="12">
			        <Form label-position="right" :label-width="80">
						<FormItem label="周一价格：">
							<Input></Input>
				        </FormItem>
						<FormItem label="周二价格：">
							<Input></Input>
				        </FormItem>
						<FormItem label="周三价格：">
							<Input></Input>
				        </FormItem>
						<FormItem label="周四价格：">
							<Input></Input>
				        </FormItem>
						<FormItem label="周五价格：">
							<Input></Input>
				        </FormItem>
						<FormItem label="周六价格：">
							<Input></Input>
				        </FormItem>
						<FormItem label="周日价格：">
							<Input></Input>
				        </FormItem>
						<FormItem>
					        <Button type="primary">保存</Button>
					        <Button type="ghost" style="margin-left: 8px">返回</Button>
					    </FormItem>
				    </Form>
	        	</Col>
	        </Row>
        </TabPane>
        <TabPane label="日价格浮动" name="day">
        <Row>
        	<Col span="10">
        		<Form label-position="right" :label-width="80">
					<FormItem label="当前日期：">2017-07-09 星期四</FormItem>
					<FormItem label="浮动价格：">
						<Input></Input>
			        </FormItem>
					<FormItem label="浮动说明：">
						<Input type="textarea" :rows="10"></Input>
			        </FormItem>
					<FormItem>
				        <Button type="primary">保存</Button>
				        <Button type="ghost" style="margin-left: 8px">返回</Button>
				    </FormItem>
			    </Form>
        	</Col>
        	<Col span="14">
				日历
        	</Col>
        </Row>
        </TabPane>
    </Tabs>
</template>