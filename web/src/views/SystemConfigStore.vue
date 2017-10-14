<style scoped>
.photos{
    margin: 8px -8px;
    .img-list{
        position: relative;
        float: left;
        width: 140px;
        height: 140px;
        text-align: center;
        line-height: 140px;
        margin: 8px;
        img{
            width: 100%;
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
}
</style>

<template>
    <Tabs value="info">
        <TabPane label="基本信息" name="info">
            <Row>
                <Col span="12">
                    <Form label-position="right" :label-width="80">
                        <FormItem label="门店名称：">
                            <Input></Input>
                        </FormItem>
                        <FormItem label="联系人：">
                            <Input></Input>
                        </FormItem>
                        <FormItem label="联系方式：">
                            <Input></Input>
                        </FormItem>
                        <FormItem label="门店地址：">
                            <Input></Input>
                        </FormItem>
                        <FormItem label="门店介绍：">
                            <Input type="textarea" :rows="10"></Input>
                        </FormItem>
                        <FormItem>
                            <Button type="primary">保存</Button>
                        </FormItem>
                    </Form>
                </Col>
            </Row>
        </TabPane>
        <TabPane label="开关设置" name="setting">
            <Row>
                <Col span="12">
                    <Form label-position="right" :label-width="120">
                        <FormItem label="开启自动退房：">
                            <RadioGroup >
                                <Radio label="0">是</Radio>
                                <Radio label="1">否</Radio>
                            </RadioGroup>
                        </FormItem>
                        <FormItem label="每日退房时间：">
                            <TimePicker type="time" placeholder="选择时间"></TimePicker>
                        </FormItem>
                        <FormItem label="开启预订：">
                            <RadioGroup >
                                <Radio label="0">是</Radio>
                                <Radio label="1">否</Radio>
                            </RadioGroup>
                        </FormItem>
                        <FormItem label="预订房保留时间：">
                            <TimePicker type="time" placeholder="选择时间"></TimePicker>
                        </FormItem>
                        <FormItem label="开启钟点房：">
                            <RadioGroup >
                                <Radio label="0">是</Radio>
                                <Radio label="1">否</Radio>
                            </RadioGroup>
                        </FormItem>
                        <FormItem label="钟点房开放时间：">
                            <TimePicker type="timerange" placeholder="选择时间"></TimePicker>
                        </FormItem>
                        <FormItem label="钟点房时长：">
                            <InputNumber :max="12" :min="1" :step="0.5"></InputNumber>&nbsp;&nbsp;小时
                        </FormItem>
                        <FormItem>
                            <Button type="primary">保存</Button>
                        </FormItem>
                    </Form>
                </Col>
            </Row>
        </TabPane>
        <TabPane label="上传相册" name="photo">
            <Upload multiple action="" :show-upload-list="false">
                <Button type="ghost"><i class="fa fa-upload fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;选择要上传的文件</Button>
            </Upload>
            <div class="photos">
                <div v-for="i in imgNum" class="img-list">
                    <img src="../images/timg.jpg" alt="">
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
                <div class="cls"></div>
            </div>
            <Modal title="查看图片" v-model="visible">
                <img :src="'/src/images/timg.jpg'" v-if="visible" style="width: 100%">
            </Modal>
        </TabPane>
    </Tabs>
</template>

<script>
    export default {
        data () {
            return {
                formItem:{
                    select:'2'
                },
                imgNum:25,
                visible:false
            }
        },
        methods:{
            handleView() {
                this.visible = true;
            },
            handleRemove() {}
        }
    }
</script>