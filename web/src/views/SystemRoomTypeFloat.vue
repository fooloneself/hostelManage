<template>
	<Tabs value="week">
        <TabPane label="周价格浮动" name="week">
	        <Row>
	        	<Col span="12">
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
                }
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