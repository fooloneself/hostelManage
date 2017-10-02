<template>
<Tabs value="info">
    <TabPane label="个人信息" name="info">
    </TabPane>
    <TabPane label="消费情况" name="consume">
        <Table :columns="columns" :data="data" stripe></Table>
        <div class="mb"></div>
        <Page :total="100" show-total></Page>
    </TabPane>
</Tabs>
</template>

<script>
    export default {
        data () {
            return {
                columns: [
                    {
                        title: '序号',
                        width: 60
                    },
                    {
                        title: '入住房型',
                    },
                    {
                        title: '入住房号',
                    },
                    {
                        title: '支付方式',
                    },
                    {
                        title: '下单方式',
                    },
                    {
                        title: '消费金额',
                    },
                    {
                        title: '消费时间',
                    },
                    {
                        title: '付费方式',
                    }
                ],
                data: [
                    {},{},{},{},{},{},{},{},{},{}
                ]
            }
        }
    }
</script>