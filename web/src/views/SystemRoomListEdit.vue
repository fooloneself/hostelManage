<style scoped>
.photos{
    margin: 8px -8px;
    .img-list{
        position: relative;
        display: inline-block;
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
                    <Form V-model="formItem" label-position="right" :label-width="100">
                        <FormItem label="房屋类型：">
                            <Select v-model="formItem.select" placeholder="请选择">
                                <Option value="beijing">标准房</Option>
                                <Option value="shanghai">大床房</Option>
                                <Option value="shenzhen">豪华房</Option>
                            </Select>
                        </FormItem>
                        <FormItem label="房间号：">
                            <Input></Input>
                        </FormItem>
                        <FormItem label="是否锁房：">
                            <RadioGroup v-model="formItem.radio">
                                <Radio label="0">是</Radio>
                                <Radio label="1">否</Radio>
                            </RadioGroup>
                        </FormItem>
                        <FormItem label="房间配套：">
                            <CheckboxGroup>
                                <Checkbox label="吃饭"></Checkbox>
                                <Checkbox label="睡觉"></Checkbox>
                                <Checkbox label="跑步"></Checkbox>
                                <Checkbox label="看电影"></Checkbox>
                            </CheckboxGroup>
                        </FormItem>
                        <FormItem label="房间说明：">
                            <Input type="textarea" :rows="5"></Input>
                        </FormItem>
                        <FormItem>
                            <Button type="primary">保存</Button>
                            <Button type="ghost" style="margin-left: 8px">返回</Button>
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
            </div>
            <Modal title="查看图片" v-model="visible">
                <img :src="'/src/images/timg.jpg'" v-if="visible" style="width: 100%">
            </Modal>
        </TabPane>
    </Tabs>
</template>

<script>
    export default{
    	data () {
    		return {
    			formItem:{
                    select:'beijing',
                    radio:'1'
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