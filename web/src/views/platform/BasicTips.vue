<template>
<div>
    <Form inline class="fr">
        <FormItem>
            <Select placeholder="回复状态" v-model="option" style="width: 100px;">
                <Option value="">全部</Option>
                <Option value="1">未回复</Option>
                <Option value="2">已回复</Option>
            </Select>
        </FormItem>
        <FormItem>
            <Button type="primary">查询</Button>
        </FormItem>
    </Form>
    <div class="cls"></div>
    <Collapse v-model="value" accordion>
    <template v-for="i in 5">
        <Panel>
            李波美 - 2017/10/10
            <div slot="content">李波美是一位工业设计师，现任Apple公司设计师兼资深副总裁，英国爵士。他曾参与设计了iPod，iMac，iPhone，iPad等众多苹果产品。除了乔布斯，他是对苹果那些著名的产品最有影响力的人。
            <Alert class="mt" closable>
                <span slot="desc">成功的提示描述文案成功的提示描述文案成功的提示描述文案成功的提示描述文案成功的提示描述文案</span>
                <span slot="close">删除</span>
            </Alert>
            </div>
        </Panel>
        <Panel>
            李波美 - 2017/10/10
            <div slot="content">李波美是一位工业设计师，现任Apple公司设计师兼资深副总裁，英国爵士。他曾参与设计了iPod，iMac，iPhone，iPad等众多苹果产品。除了乔布斯，他是对苹果那些著名的产品最有影响力的人。
                <div class="mt">
                    <Input type="textarea" :rows="3" placeholder="请输入回复内容..."></Input>
                    <Button type="primary" class="mt">回复</Button>
                </div>
            </div>
        </Panel>
    </template>
    </Collapse>
    <div class="mb"></div>
    <Page :total="100" show-total></Page>
</div>
</template>
<script>
    export default {
        data () {
            return {
                option:'1',
                value:'0'
            }
        },
        methods:{
            turnUrl:function(url,query){
                this.$router.push(url)
            }
        }
    }
</script>