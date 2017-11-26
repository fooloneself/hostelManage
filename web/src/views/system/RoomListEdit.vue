<style scoped>
.img-list{
    position: relative;
    height: 102px;
    line-height: 102px;
    text-align: center;
    margin: 8px 0;
    background: #dddee1;
    img{
        width: auto;
        height: 100%;
    }
    .img-cover{
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
        font-size: 30px;
        color: #FFF;
    }
    &:hover .img-cover{
        display: block;
    }
}
</style>

<template>
<div style="margin-top: -24px;">
    <Tabs value="info">
        <TabPane label="基本信息" name="info">
            <Row>
                <Col span="12">
                    <Form v-model="formItem" label-position="right" :label-width="100">
                        <FormItem label="房屋类型：">
                            <Select v-model="formItem.type" placeholder="请选择">
                                <Option v-for="type in types" :value="type.id" :key="type.id">{{type.name}}</Option>
                            </Select>
                        </FormItem>
                        <FormItem label="房间号：">
                            <Input v-model="formItem.number"></Input>
                        </FormItem>
                        <FormItem label="是否锁房：">
                            <Switch v-model="formItem.lock" :true-value="1" :false-value="0">
                                <span slot="open">是</span>
                                <span slot="close">否</span>
                            </Switch>
                        </FormItem>
                        <FormItem label="房间配套：">
                            <CheckboxGroup v-model="formItem.servers">
                                <Checkbox v-for="server in servers" :label="server.key">{{server.value}}</Checkbox>
                            </CheckboxGroup>
                        </FormItem>
                        <FormItem label="房间说明：">
                            <Input v-model="formItem.introduce" type="textarea" :rows="5"></Input>
                        </FormItem>
                        <FormItem>
                            <Button @click="baseSubmit" type="primary">保存</Button>
                            <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
                        </FormItem>
                    </Form>
                </Col>
            </Row>
        </TabPane>
        <TabPane label="上传相册" name="photo">
            <Upload multiple action="" :show-upload-list="false">
                <Button type="primary"><i class="fa fa-upload fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;选择要上传的文件</Button>
            </Upload>
            <Row :gutter="16" style="margin-top: 8px;">
                <Col v-for="i in imgNum" span="3">
                    <div class="img-list">
                        <img src="/src/images/timg.jpg" alt="">
                        <div class="img-cover">
                            <Tooltip placement="top" content="设为封面">
                                <Icon type="ios-home-outline" @click.native="handleView()"></Icon>
                            </Tooltip>
                            <Tooltip placement="top" content="查看图片">
                                <Icon type="ios-eye-outline" @click.native="handleView()" style="margin: 0 8px;"></Icon>
                            </Tooltip>
                            <Tooltip placement="top" content="删除图片">
                                <Icon type="ios-trash-outline" @click.native="handleRemove()"></Icon>
                            </Tooltip>
                        </div>
                    </div>
                </Col>
            </Row>
            <Modal title="查看图片" v-model="visible">
                <img :src="'/src/images/timg.jpg'" v-if="visible" style="width: 100%;">
            </Modal>
        </TabPane>
    </Tabs>
</div>
</template>

<script>
    export default{
    	data () {
    		return {
    			formItem:{
    			    id: this.$route.params.id,
                    type:'',
                    number:'',
                    lock: '0',
                    servers: [],
                    introduce: ''
                },
                servers:[],
                types:[],
                imgNum:25,
                visible:false
    		}
    	},
    	mounted (){
    	    var that=this;
    	    this.host.post('roomEditPageInfo',{id:this.$route.params.id}).then(function(res){
    	        if(res.isSuccess()){
    	            var data=res.data();
    	            if(data.room!=null && data.room!=''){
    	                that.formItem=data.room;
    	            }
    	            that.servers=data.servers;
    	            that.types=data.types;
    	        }else{
    	            that.$Notice.info({
                        title: '提示',
                        desc: res.error()
                    });
    	        }
    	    })
    	},
        methods:{
            handleView() {
                this.visible = true;
            },
            goBack (){
                this.$router.go(-1);
            },
            handleRemove() {},
            baseSubmit (){
                this.host.post('roomRecord',this.formItem).then(function(res){
                    if(res.isSuccess()){
                        this.$router.push('/admin/roomList');
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