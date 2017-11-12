<template>
<Row :gutter="24">
	<Col span="10">
		<Form v-model="formItem" label-position="right" :label-width="80">
			<FormItem label="角色名称：">
				<Input v-model="formItem.name"></Input>
	        </FormItem>
			<FormItem label="角色代码：">
				<Input v-model="formItem.code"></Input>
	        </FormItem>
	        <FormItem label="门店可选：">
                <Switch v-model="formItem.mchCan" :true-value="1" :false-value="0">
                    <span slot="open">是</span>
                    <span slot="close">否</span>
                </Switch>
	        </FormItem>
			<FormItem label="角色说明：">
	            <Input v-model="formItem.mark" type="textarea" :rows="10"></Input>
	        </FormItem>
			<FormItem>
	            <Button @click="submit" type="primary">保存</Button>
                <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
	        </FormItem>
	    </Form>		
	</Col>
	<Col span="10" offset="1">
		选择权限：
		<Tree :data="baseData" show-checkbox></Tree>
	</Col>
</Row>
</template>

<script>
export default{
	data () {
		return {
			formItem:{
				id: this.$route.params.roleId,
				name: '',
				code: '',
				mchCan: 0,
				mark: ''
			},
			baseData: [{
                expand: true,
                title: 'parent 1',
                children: [{
                    title: 'parent 1-0',
                    expand: true,
                    disabled: true,
                    children: [{
                        title: 'leaf',
                        disableCheckbox: true
                    }, {
                        title: 'leaf',
                    }]
                }, {
                    title: 'parent 1-1',
                    expand: true,
                    checked: true,
                    children: [{
                        title: 'leaf',
                    }]
                }]
            }]
		}
	},
    mounted (){
        if(this.$route.params.roleId>0){
            var that=this;
            this.host.post('platformRoleDetail',{id: this.$route.params.roleId}).then(function(res){
                if(res.isSuccess()){
                    that.formItem=res.data();
                }else{
                    this.$Notice.info({
                        title: '错误提示',
                        desc: res.error()
                    })
                }
            })
        }
    },
    methods:{
        goBack (){
            this.$router.go(-1);
        },
        submit (){
            this.host.post('platformRoleEdit',this.formItem).then(function(res){
                if(res.isSuccess()){
                    this.$router.go(-1);
                }else{
                    this.$Notice.info({
                        title: '错误提示',
                        desc: res.error()
                    })
                }
            })
        }
    }
}
</script>