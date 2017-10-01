<template>
    <Tabs value="info">
        <TabPane label="基本信息" name="info">
            <div class="form">
                <Form V-model="formItem" label-position="right" :label-width="100">
                    <FormItem label="房屋类型：">
                        <Select v-model="formItem.select" placeholder="请选择">
                            <Option value="beijing">北京市</Option>
                            <Option value="shanghai">上海市</Option>
                            <Option value="shenzhen">深圳市</Option>
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
            </div>
        </TabPane>
        <TabPane label="上传相册" name="photo">
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
            }
		}
	}
}
</script>