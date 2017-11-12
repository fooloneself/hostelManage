<template>
<Row>
    <Col span="12">
        <Form v-model="formItem" label-position="right" :label-width="100">
            <FormItem label="房屋类型：">
                <Input v-model="formItem.name"></Input>
            </FormItem>
            <FormItem label="默认价格：">
                <Input v-model="formItem.defaultPrice"></Input>
            </FormItem>
            <FormItem label="允许钟点房：">
                <Switch v-model="formItem.allowHourRoom" :true-value="1" :false-value="0">
                    <span slot="open">是</span>
                    <span slot="close">否</span>
                </Switch>
            </FormItem>
            <FormItem label="钟点房价格：" v-show="formItem.allowHourRoom">
                <Input v-model="formItem.hourRoomPrice"></Input>
            </FormItem>
            <FormItem label="房型说明：">
                <Input v-model="formItem.introduce" type="textarea" :rows="5"></Input>
            </FormItem>
            <FormItem>
                <Button type="primary" @click="submit">保存</Button>
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
			formItem: {
			    id: this.$route.params.id,
                name: '',
                defaultPrice: null,
                allowHourRoom: 1,
                hourRoomPrice: null,
                introduce: ''
			},
		}
	},
	mounted (){
	    if(this.$route.params.id>0){
            var that=this;
            this.host.post('roomType',{id: this.$route.params.id}).then(function(res){
                if(res.isSuccess()){
                    that.formItem={
                        id: parseInt(res.data().id),
                        name: res.data().name,
                        defaultPrice: res.data().default_price,
                        allowHourRoom: res.data().allow_hour_room,
                        hourRoomPrice: res.data().hour_room_price,
                        introduce: res.data().introduce
                    }
                }else{
                    that.$Notice.info({
                        title: '提示',
                        desc: res.error()
                    });
                }
            })
        }
	},
	methods:{
	    submit: function(){
	        this.host.post('roomTypeEdit',this.formItem).then(function(res){
	            if(res.isSuccess()){
	                this.$router.push('/admin/roomType');
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