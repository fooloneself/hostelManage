<template>
<Row>
	<Col span="12">
		<Form v-model="formItem" label-position="right" :label-width="80">
			<FormItem label="字典名称：">{{label}}</FormItem>
			<FormItem label="数据项：">
				<Input v-model="formItem.key"></Input>
	        </FormItem>
			<FormItem label="数据值：">
				<Input v-model="formItem.value"></Input>
	        </FormItem>
			<FormItem label="排序：">
				<Input v-model="formItem.order"></Input>
	        </FormItem>
			<FormItem>
	            <Button type="primary" @click="submit">保存</Button>
                <Button type="ghost" @click="goBack" class="icon-ml">返回</Button>
	        </FormItem>
	    </Form>
	</Col>
</Row>
</template>

<script>
export default{
	data () {
		return {
		    formItem: {
		        id: this.$route.params.id,
                key: '',
                value: '',
                order: 0,
                code: this.$route.params.code
		    },
		    label: ''
		}
	},
	mounted (){
	    var that=this;
	    this.host.post('dictionaryViewByCode',{code:this.$route.params.code}).then(function(res){
            if(res.isSuccess()){
                if(res.data()!=null)that.label=res.data().label;
            }else{
                that.$Notice.info({
                    title: '提示',
                    desc: res.error()
                });
            }
	    })
	    if(this.$route.params.id>0){
	        this.host.post('dictionaryItemView',{id: this.$route.params.id}).then(function(res){
                if(res.isSuccess()){
                    if(res.data()!=null){
                        that.formItem.key=res.data().key;
                        that.formItem.value=res.data().value;
                        that.formItem.order=res.data().order;
                    }
                }else{
                    this.$Notice.info({
                        title: '提示',
                        desc: res.error()
                    });
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
            this.host.post('dictionaryItemRecord',this.formItem).then(function(res){
                if(res.isSuccess()){
                    this.$router.push('/basicDictInfo/'+that.$route.params.code);
                }else{
                    alert(res.error());
                }
            })
		}
	}
}
</script>