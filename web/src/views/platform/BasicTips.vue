<template>
<div>
    <Collapse v-model="value" accordion>
    <template v-for="i in 10">
        <Panel>
            李波美 - 2017/10/10
            <div class="fr">
                <Button type="text">回复</Button>
            </div>
            <p slot="content">李波美是一位工业设计师，现任Apple公司设计师兼资深副总裁，英国爵士。他曾参与设计了iPod，iMac，iPhone，iPad等众多苹果产品。除了乔布斯，他是对苹果那些著名的产品最有影响力的人。
            <Alert class="mt">回复内容：
                <span slot="desc">成功的提示描述文案成功的提示描述文案成功的提示描述文案成功的提示描述文案成功的提示描述文案</span>
            </Alert>
            </p>
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