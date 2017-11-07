<template>
<Row>
	<Col span="24">
	    <Alert show-icon>
	        <Icon type="ios-lightbulb-outline" slot="icon"></Icon>
	        <template slot="desc">
	            小提示：会员生日适用开关打开时，无论会员处于什么等级，当前日期是否有效，会员生日当天都可以享受该活动；
	        </template>
	    </Alert>
	    <div class="mb"></div>
	</Col>
	<Col span="10">
		<Form label-position="right" :label-width="100">
			<FormItem label="折扣名称：">
				<Input placeholder="例：打九折"></Input>
	        </FormItem>
			<FormItem label="折扣系数：">
				<Input placeholder="例：0.9"></Input>
	        </FormItem>
	        <FormItem label="活动适用范围：">
		        <CheckboxGroup>
			        <Checkbox label="房费"></Checkbox>
			        <Checkbox label="商品"></Checkbox>
			    </CheckboxGroup>
	        </FormItem>
	        <FormItem label="活动可叠加：">
		        <Switch>
			        <span slot="open">是</span>
			        <span slot="close">否</span>
			    </Switch>
	        </FormItem>
			<FormItem label="适用会员等级：">
				<Select v-model="model" multiple>
			        <Option v-for="item in typeList" :value="item.value" :key="item.value">{{ item.label }}</Option>
			    </Select>
	        </FormItem>
			<FormItem label="活动说明：">
	            <Input type="textarea" :rows="5"></Input>
	        </FormItem>
			<FormItem>
	            <Button type="primary">保存</Button>
	            <Button type="primary" @click="turnUrl('/admin/planEdit')" class="icon-ml">保存并添加执行计划</Button>
                <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
	        </FormItem>
	    </Form>
	</Col>
	<Col span="10" offset="1">
		选择房间：
		<Tree :data="baseData" show-checkbox></Tree>
	</Col>
</Row>
</template>

<script>
export default{
	data () {
		return {
			typeList: [
                {
                    value: '0',
                    label: '非会员'
                },
                {
                    value: '1',
                    label: '普通会员'
                },
                {
                    value: '2',
                    label: '黄金会员'
                },
                {
                    value: '3',
                    label: '铂金会员'
                },
                {
                    value: '4',
                    label: '钻石会员'
                }
            ],
            model: ['0'],
            baseData: [{
                expand: true,
                title: '全部房间',
                children: [
	                {
	                    title: '房间类型 1',
	                    children: [
		                    {
		                        title: '房号 1-1',
		                    },
		                    {
		                        title: '房号 1-1',
		                    },
		                    {
		                        title: '房号 1-1',
		                    },
	                    ]
	                },
	                {
	                    title: '房间类型 1',
	                    children: [{
	                        title: '房号 1-1',
	                    }]
	                },
	                {
	                    title: '房间类型 1',
	                    children: [{
	                        title: '房号 1-1',
	                    }]
	                },
                ]
            }]
		}
	},
	methods:{
		turnUrl:function(url,query){
            this.$router.push(url)
        },
		goBack(){
	        this.$router.go(-1);
	    }
	}
}
</script>