<template>
<Row>
	<Col span="6">
		<h3 class="mb">活动执行计划</h3>
		<Form label-position="right" :label-width="80">
            <FormItem label="活动类型：">折扣</FormItem>
			<FormItem label="活动名称：">活动名称</FormItem>
			<FormItem label="时间周期：">
				<DatePicker type="daterange" placeholder="选择日期"></DatePicker>
	        </FormItem>
	        <FormItem>
				<Button type="primary">添加</Button>
				<Button type="ghost" @click="goBack" class="icon-ml">返回</Button>
	        </FormItem>
		</Form>		
	</Col>
	<Col span="17" offset="1">
		<Table :columns="columns" :data="data" stripe></Table>
		<div class="mb"></div>
    	<Page :total="100" show-total></Page>
	</Col>
</Row>
</template>

<script>
export default{
	data () {
		return {
            columns: [
                {
                    title: '序号',
                    width: 60
                },
                {
                    title: '活动执行计划'
                },
                {
                    title: '操作',
                    key: 'action',
                    width: 100,
                    render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    },
                                    on: {
                                        click: ()=>{
                                            this.$Modal.confirm({
                                                title:'提示',
                                                content:'请确认是否要删除？'
                                            });
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                }
            ],
            data: [
                {},{},{},{},{},{},{},{},{},{}
            ]
		}
	},
	methods:{
        goBack(){
            this.$router.go(-1);
        }
	}
}
</script>