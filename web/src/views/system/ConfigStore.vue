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
                <Col span="10">
                    <Form v-model="storeBase" label-position="right" :label-width="80">
                        <FormItem label="门店名称：">
                            <Input v-model="storeBase.name"></Input>
                        </FormItem>
                        <FormItem label="退房时间：">
                            <TimePicker v-model="storeSetting.checkOutTime" :value="storeSetting.checkOutTime" type="time" placeholder="选择时间"></TimePicker>
                        </FormItem>
                        <FormItem label="联系人：">
                            <Input v-model="storeBase.contactName"></Input>
                        </FormItem>
                        <FormItem label="联系方式：">
                            <Input v-model="storeBase.mobile"></Input>
                        </FormItem>
                        <FormItem label="门店地址：">
                            <Input v-model="storeBase.address"></Input>
                        </FormItem>
                        <FormItem label="门店介绍：">
                            <Input v-model="storeBase.introduce" type="textarea" :rows="10"></Input>
                        </FormItem>
                        <FormItem>
                            <Button @click="setStoreBase" type="primary">保存</Button>
                        </FormItem>
                    </Form>
                </Col>
            </Row>
        </TabPane>
        <TabPane label="开关设置" name="setting">
            <Row>
                <Col span="10">
                    <Form v-model="storeSetting" label-position="right" :label-width="120">
                        <FormItem label="开启自动退房：">
                            <RadioGroup v-model="storeSetting.orderAutoClose">
                                <Radio label="1">是</Radio>
                                <Radio label="0">否</Radio>
                            </RadioGroup>
                        </FormItem>
                        <FormItem label="开启预订：">
                            <RadioGroup v-model="storeSetting.reserveSwitch">
                                <Radio label="1">是</Radio>
                                <Radio label="0">否</Radio>
                            </RadioGroup>
                        </FormItem>
                        <FormItem label="预订房保留时间：">
                            <TimePicker v-model="storeSetting.reserveRetentionTime" :value="storeSetting.reserveRetentionTime" type="time" placeholder="选择时间"></TimePicker>
                        </FormItem>
                        <FormItem label="开启钟点房：">
                            <RadioGroup v-model="storeSetting.hourRoomSwitch">
                                <Radio label="1">是</Radio>
                                <Radio label="0">否</Radio>
                            </RadioGroup>
                        </FormItem>
                        <FormItem label="钟点房开放时间：">
                            <TimePicker v-model="storeSetting.hourRoomRange" :value="storeSetting.hourRoomRange" type="timerange" placeholder="选择时间"></TimePicker>
                        </FormItem>
                        <FormItem label="钟点房时长：">
                            <InputNumber :max="12" :min="1" :step="0.5"></InputNumber>&nbsp;&nbsp;小时
                        </FormItem>
                        <FormItem>
                            <Button @click="setSwitch" type="primary">保存</Button>
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
    export default {
        data () {
            return {
                storeBase:{},
                storeSetting:{
                    orderAutoClose: 0,
                    reserveSwitch: 0,
                    hourRoomSwitch: 0
                },
                imgNum:25,
                visible:false
            }
        },
        mounted (){
            var that=this;
            this.host.post('storeConfig').then(function(res){
                if(res.isSuccess()){
                    if(res.data()){
                        if(res.data().base){
                            that.storeBase=res.data().base;
                        }
                        if(res.data().setting){
                            if(res.data().setting.reserveRetentionTime>0){
                                var date=new Date(parseInt(res.data().setting.reserveRetentionTime)*1000);
                                res.data().setting.reserveRetentionTime=that.getHourOfDate(date);
                            }else{
                                res.data().setting.reserveRetentionTime='';
                            }
                            that.storeSetting=res.data().setting;
                        }
                    }
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
            handleRemove() {},
            setStoreBase (){
                this.host.post('storeBaseSet',this.storeBase).then(function(res){
                    if(res.isSuccess()){
                        this.$Notice.info({
                            title: '提示',
                            desc: '设置成功'
                        });
                    }else{
                        this.$Notice.info({
                            title: '提示',
                            desc: res.error()
                        });
                    }
                })
            },
            getHourOfDate(date){
                if(!date)return '';
                date=new Date(date);
                return this.strPad(date.getHours())+':'+this.strPad(date.getMinutes())+':'+this.strPad(date.getSeconds());
            },
            strPad (num){
                if(num<10){
                    return '0'+num;
                }else{
                    return num
                }
            },
            getTime(date){
                if(!date)return 0;
                return Math.floor(Date.parse(new Date(date))/1000)%86400;
            },
            setSwitch (){
                var param={
                    orderAutoClose: parseInt(this.storeSetting.orderAutoClose),
                    reserveSwitch: parseInt(this.storeSetting.reserveSwitch),
                    hourRoomSwitch: parseInt(this.storeSetting.hourRoomSwitch),
                    checkOutTime:this.getHourOfDate(this.storeSetting.checkOutTime),
                    reserveRetentionTime: this.getTime(this.storeSetting.reserveRetentionTime),
                    hourRoomStartTime:this.getHourOfDate(this.storeSetting.hourRoomRange[0]),
                    hourRoomEndTime:this.getHourOfDate(this.storeSetting.hourRoomRange[1])
                }
                this.host.post('storeSet',param).then(function(res){
                    if(res.isSuccess()){
                        this.$Notice.info({
                            title: '提示',
                            desc: '设置成功'
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