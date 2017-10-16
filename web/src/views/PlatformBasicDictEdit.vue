<template>
<Row>
	<Col span="24">
		<Button type="ghost" @click="goBack"><i class="fa fa-chevron-left icon-mr" aria-hidden="true"></i>返回</Button>
		<div class="mb"></div>
	</Col>
	<Col span="12">
		<Form v-model="formItem" label-position="right" :label-width="80">
			<FormItem label="字典名称：">
				<Input v-model="formItem.label"></Input>
	        </FormItem>
			<FormItem label="唯一代码：">
				<Input v-model="formItem.code"></Input>
	        </FormItem>
			<FormItem label="菜单说明：">
	            <Input v-model="formItem.introduce" type="textarea" :rows="10"></Input>
	        </FormItem>
			<FormItem>
	            <Button type="primary" @click="submit">保存</Button>
	        </FormItem>
	    </Form>
	</Col>
</Row>
</template>

<script>
export default{
	data () {
		return {
			formItem:{
			    id: this.$route.params.id,
				label:'',
				code: '',
				introduce: ''
			}
		}
	},
	mounted (){
	    if(this.$route.params.id>0){
	        var that=this;
	        this.host.post('dictionaryView',{id:this.$route.params.id}).then(function(res){
	            if(res.isSuccess()){
	                if(res.data()!=null){
	                    that.formItem.label=res.data().label;
                        that.formItem.code=res.data().code;
                        that.formItem.introduce=res.data().introduce;
	                }
	            }else{
	                alert(res.errro());
	            }
	        })
	    }
	},
	methods:{
		goBack:function(){
			history.go(-1);
		},
		submit:function(){
		    var that=this;
		    this.host.post('dictionaryRecord',this.formItem).then(function(res){
		        if(res.isSuccess()){
		            alert("操作成功");
		            that.goBack();
		        }else{
		            alert(res.error());
		        }
		    })
		}
	}
}
</script>