<template>
<div>
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
                        <FormItem>
                            <Button type="primary">保存</Button>
                        </FormItem>
                    </Form>
                </Col>
            </Row>
        </TabPane>
        <TabPane label="上传相册" name="photo">
        </TabPane>
    </Tabs>
</div>
</template>

<script>
    export default {
        data () {
            return {
                
            }
        }
    }
</script>