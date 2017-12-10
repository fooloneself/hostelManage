<template>
<div>
    <Alert show-icon>
        <Icon type="ios-lightbulb-outline" slot="icon"></Icon>
        <template slot="desc">
            小提示：会员生日福利所选择的福利活动不受活动执行时间限制；
        </template>
    </Alert>
    <Form v-model="form">
        <div class="mb"></div>
        生日福利：会员生日前后<InputNumber v-model="form.expire" class="icon-ml icon-mr"></InputNumber>天可享受
        <div class="mb"></div>
        <Transfer
            :titles="['可分配活动', '已分配活动']"
            :data="activities"
            :target-keys="form.activityIds"
            :list-style="listStyle"
            :render-format="render"
            :operations="['移去活动','添加活动']"
            filterable
            @on-change="handleChange">
        </Transfer>
        <div class="mb"></div>
        <Button type="primary" @click="setBirthdayWelfare">保存</Button>
        <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
    </Form>
</div>
</template>
<script>
    export default {
        data () {
            return {
                listStyle: {
                    width: '400px',
                    height: '500px'
                },
                activities:[],
                form:{
                    rankId:this.$route.params.rankId,
                    activityIds:[],
                    expire:0
                }
            }
        },
        mounted(){
            var that=this;
            this.host.post('merchantMemberBirthdayWelfare',{rankId: this.$route.params.rankId}).then(function(res){
                if(res.isSuccess()){
                    that.form.activityIds=res.data().selected;
                    that.activities=that.handleActivities(res.data().activities);
                }else{
                    this.$Notice.info({
                        title:'错误提示',
                        desc: res.error()
                    })
                }
            })
        },
        methods: {
            handleChange (newTargetKeys,direction,moveKeys) {
                if(direction=='right'){
                    for(var key in moveKeys){
                        var index=this.form.activityIds.indexOf(moveKeys[key]);
                        if(index<0){
                            this.form.activityIds.push(moveKeys[key]);
                        }
                    }
                }else{
                    for(var key in moveKeys){
                        var index=this.form.activityIds.indexOf(moveKeys[key]);
                        if(index!==false){
                            this.form.activityIds.splice(index,1);
                        }
                    }
                }
            },
            render (item) {
                return item.label + ' - ' + item.description;
            },
            goBack(){
                this.$router.go(-1);
            },
            setBirthdayWelfare(){
                var that=this;
                this.host.post('merchantMemberBirthdayWelfareSet',this.form).then(function(res){
                    if(res.isSuccess()){
                        this.$router.push('/admin/memberRank');
                    }else{
                        this.$Notice.info({
                            title: '错误提示',
                            desc: res.error()
                        })
                    }
                })
            },
            handleActivities(activities){
                let a=[];
                for(var key in activities){
                    a.push({
                        key: activities[key].id,
                        label:activities[key].name,
                        description:activities[key].mark
                    });
                }
                return a;
            }
        }
    }
</script>