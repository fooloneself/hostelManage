<template>
<div>
    <Alert show-icon>
        <Icon type="ios-lightbulb-outline" slot="icon"></Icon>
        <template slot="desc">
            小提示：会员生日福利所选择的福利活动不受活动执行时间限制；
        </template>
    </Alert>
    <div class="mb"></div>
    生日福利：会员生日前后<InputNumber class="icon-ml icon-mr"></InputNumber>天可享受
    <div class="mb"></div>
    <Transfer
        :titles="['已分配活动', '可分配活动']"
        :data="data3"
        :target-keys="targetKeys3"
        :list-style="listStyle"
        :render-format="render3"
        :operations="['添加活动','移去活动']"
        filterable
        @on-change="handleChange3">
    </Transfer>
    <div class="mb"></div>
    <Button type="primary">保存</Button>
    <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
</div>
</template>
<script>
    export default {
        data () {
            return {
                data3: this.getMockData(),
                targetKeys3: this.getTargetKeys(),
                listStyle: {
                    width: '400px',
                    height: '500px'
                }
            }
        },
        methods: {
            getMockData () {
                let mockData = [];
                for (let i = 1; i <= 20; i++) {
                    mockData.push({
                        key: i.toString(),
                        label: '折扣' + i,
                        description: '折扣' + i + '的描述信息'
                    });
                }
                return mockData;
            },
            getTargetKeys () {
                return this.getMockData()
                        .filter(() => Math.random() * 2 > 1)
                        .map(item => item.key);
            },
            handleChange3 (newTargetKeys) {
                this.targetKeys3 = newTargetKeys;
            },
            render3 (item) {
                return item.label + ' - ' + item.description;
            },
            reloadMockData () {
                this.data3 = this.getMockData();
                this.targetKeys3 = this.getTargetKeys();
            },
            goBack(){
                this.$router.go(-1);
            }
        }
    }
</script>