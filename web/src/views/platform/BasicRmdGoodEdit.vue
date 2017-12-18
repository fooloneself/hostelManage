<style scoped>
.upload-img{
	color: #80848f;
	padding: 50px;
	text-align: center;
	border: 1px dashed #dddee1;
	cursor: pointer;
}
</style>

<template>
<Row>
	<Col span="10">
		<Form label-position="right" :label-width="80">
			<FormItem label="商品标题：">
				<Input></Input>
	        </FormItem>
	        <FormItem label="商品类型：">
				<Select>
		            <Option value="1">类型一</Option>
		            <Option value="2">类型二</Option>
		            <Option value="3">类型三</Option>
		        </Select>
	    	</FormItem>
			<FormItem label="商品价格：">
				<Input></Input>
	        </FormItem>
			<FormItem label="推广URL：">
				<Input></Input>
	        </FormItem>
			<FormItem label="商品说明：">
	            <Input type="textarea" :rows="20"></Input>
	        </FormItem>
			<FormItem>
	            <Button type="primary">保存</Button>
                <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
	        </FormItem>
	    </Form>
	</Col>
	<Col span="10" offset="1">
		<Upload action="">
	        <div class="upload-img">
	            <Icon type="ios-cloud-upload" size="52"></Icon>
	            <p>上传缩略图</p>
	        </div>
	    </Upload>
	</Col>
</Row>
</template>

<script>
export default{
	data () {
		return {}
	},
	methods:{
		goBack:function(){
			history.go(-1);
		}
	}
}
</script>