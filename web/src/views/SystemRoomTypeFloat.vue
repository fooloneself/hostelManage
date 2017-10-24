<style scoped>
.title{
    height: 37px;
    line-height: 37px;
    font-weight: bolder;
}
</style>

<template>
<div>
	<Tabs value="week">
        <span class="title" slot="extra">房屋类型：豪华大床房</span>
        <TabPane label="周价格浮动" name="week">
	        <Row>
	        	<Col span="10">
			        <Form v-model="formItem" label-position="right" :label-width="80">
						<FormItem label="周一价格：">
							<Input v-model="formItem.monday"></Input>
				        </FormItem>
						<FormItem label="周二价格：">
							<Input v-model="formItem.tuesday"></Input>
				        </FormItem>
						<FormItem label="周三价格：">
							<Input v-model="formItem.wensday"></Input>
				        </FormItem>
						<FormItem label="周四价格：">
							<Input v-model="formItem.thursday"></Input>
				        </FormItem>
						<FormItem label="周五价格：">
							<Input v-model="formItem.friday"></Input>
				        </FormItem>
						<FormItem label="周六价格：">
							<Input v-model="formItem.saturday"></Input>
				        </FormItem>
						<FormItem label="周日价格：">
							<Input v-model="formItem.sunday"></Input>
				        </FormItem>
						<FormItem>
					        <Button @click="submit" type="primary">保存</Button>
                            <Button type="ghost" class="icon-ml">返回</Button>
					    </FormItem>
				    </Form>
	        	</Col>
	        </Row>
        </TabPane>
        <TabPane label="日价格浮动" name="day">
        <Row>
        	<Col span="6">
        		<Form label-position="right" :label-width="80">
					<FormItem label="日期范围：">
						<DatePicker type="daterange"></DatePicker>
					</FormItem>
					<FormItem label="浮动价格：">
						<Input></Input>
			        </FormItem>
					<FormItem label="浮动说明：">
						<Input type="textarea" :rows="14"></Input>
			        </FormItem>
					<FormItem>
				        <Button type="primary">添加</Button>
                        <Button type="ghost" class="icon-ml">返回</Button>
				    </FormItem>
			    </Form>
        	</Col>
        	<Col span="17" offset="1">
        		<Form inline class="tr">
					<FormItem>
						<DatePicker type="date" placeholder="请选择查询日期"></DatePicker>
					</FormItem>
			    </Form>
				<Table :columns="columns" :data="data" stripe></Table>
			    <div class="mb"></div>
			    <Page :total="100" show-total></Page>
        	</Col>
        </Row>
        </TabPane>
    </Tabs>
</div>
</template>

<script>
	export default {
	    data (){
	        return {
	            formItem: {
	                typeId: this.$route.params.typeId,
                    monday: null,
                    tuesday: null,
                    wensday: null,
                    thursday: null,
                    friday: null,
                    saturday: null,
                    sunday: null
                },
                columns: [
                    {
                        title: '序号',
                        width: 60
                    },
                    {
                        title: '日期范围',
                        width: 140
                    },
                    {
                        title: '价格',
                        width: 60
                    },
                    {
                        title: '说明'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 120,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '编辑'),
                                h('Button', {
                                    props: {
                                        type: 'text',
                                        size: 'small'
                                    }
                                }, '删除')
                            ]);
                        }
                    }
                ],
                data: [
                    {},{},{},{},{},{},{},{}
                ]
	        }
	    },
	    mounted (){
	        var that=this;
            this.host.post('roomWeekPrice',{typeId:this.$route.params.typeId}).then(function(res){
                if(res.isSuccess()){
                    if( typeof res.data()!='undefined' && res.data()!=null){
                        var data=res.data();
                        data.typeId=parseInt(data.type_id);
                        delete data.type_id;
                        delete data.mch_id;
                        that.formItem=data
                    }
                }else{
                    alert(res.error());
                }
            })
	    },
	    methods:{
	        submit: function(){
	            this.host.post('roomWeekPriceSave',this.formItem).then(function(res){
	                if(res.isSuccess()){
	                    this.$Notice.info({
                            title: '提示',
                            desc: '价格设置成功'
                        });
	                }else{
	                    this.$Notice.info({
                            title: '提示',
                            desc: res.error()
                        });
	                }
	            })
	        }
	    }
	}
</script>