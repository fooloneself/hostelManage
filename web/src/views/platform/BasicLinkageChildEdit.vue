<template>
<Row>
	<Col span="12">
		<Form :model="formItem" label-position="right" :label-width="80">
			<FormItem label="父级菜单：" v-if="parent">{{parent.label}}</FormItem>
			<FormItem label="菜单名称：">
				<Input v-model="formItem.label"></Input>
	        </FormItem>
			<FormItem label="排序：">
				<Input v-model="formItem.order"></Input>
	        </FormItem>
			<FormItem label="菜单说明：">
	            <Input v-model="formItem.introduce" type="textarea" :rows="5"></Input>
	        </FormItem>
			<FormItem>
	            <Button @click="submit" type="primary">保存</Button>
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
			formItem:{
			    id: this.$route.params.id,
			    pid: this.$route.params.pid,
				label: '',
				order: 0,
				introduce: '',
				code: this.$route.params.code
			},
			parent:null
		}
	},
	mounted (){
	    var that=this;
	    this.host.post('linkageMenuItemView',{id: this.$route.params.id,pid: this.$route.params.pid}).then(function(res){
	        if(res.isSuccess()){
                if(res.data().parent){
                    that.parent=res.data().parent;
                }
                if(res.data().current){
                    that.formItem.label=res.data().current.label;
                    that.formItem.introduce=res.data().current.introduce;
                    that.formItem.order=res.data().current.order;
                    that.formItem.introduce=res.data().current.introduce;
                }
	        }else{
	            that.$Notice.info({
	                title: '提示',
	                desc: res.error()
	            })
	        }
	    })
	},
	methods:{
	    goBack (){
	        this.$router.go(-1);
	    },
	    submit (){
	        this.host.post('linkageMenuItemRecord',this.formItem).then(function(res){
	            if(res.isSuccess()){
	                this.$router.push('/admin/basicLinkageChild/'+this.$route.params.code+'/'+this.$route.params.pid);
	            }else{
	                this.$Notice.info({
	                    title:'提示',
	                    desc: res.error()
	                })
	            }
	        })
	    }
	}
}
</script>