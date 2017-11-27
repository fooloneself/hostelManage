<template>
<Row>
	<Col span="24">
		<Alert show-icon>
	        <Icon type="ios-lightbulb-outline" slot="icon"></Icon>
	        <template slot="desc">
				小提示：您可以随意指定会员的等级，但是该会员的等级不能低于他已经达到的实际等级。
	        </template>
	    </Alert>
		<div class="mb"></div>
	</Col>
	<Col span="10">
		<Form label-position="right" :label-width="100">
			<FormItem label="姓名：">邓寒风</FormItem>
			<FormItem label="手机号：">18208166137</FormItem>
			<FormItem label="原会员等级：">等级一</FormItem>
			<FormItem label="新会员等级：">
				<Select >
		            <Option value="1">等级一</Option>
		            <Option value="2">等级二</Option>
		        </Select>
	        </FormItem>
			<FormItem label="余额：">￥45.00</FormItem>
			<FormItem label="充值金额：">
				<Input placeholder="请输入充值金额"><span slot="prepend">￥</span></Input>
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
	data () {
		return {
		}
	},
	methods:{
	    goBack (){
	        this.$router.go(-1);
	    }
	}
}
</script>