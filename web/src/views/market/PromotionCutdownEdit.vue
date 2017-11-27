<template>
<Row>
	<Col span="10">
		<Form v-model="activity" label-position="right" :label-width="100">
			<FormItem label="满减名称：">
				<Input v-model="activity.name" placeholder="例：满100减10"></Input>
	        </FormItem>
			<FormItem label="满减规则：">
				<Row>
					<Col span="11">
						<Input v-model="activity.fullCut" placeholder="例：100"><span slot="prepend">满</span></Input>
					</Col>
					<Col span="2" class="tc">~</Col>
					<Col span="11">
						<Input v-model="activity.discount" placeholder="例：10"><span slot="prepend">减</span></Input>
					</Col>
				</Row>
	        </FormItem>
	        <!-- <FormItem label="活动适用范围：">
		        <CheckboxGroup>
			        <Checkbox label="房费"></Checkbox>
			        <Checkbox label="商品"></Checkbox>
			    </CheckboxGroup>
	        </FormItem>
	        <FormItem label="活动可叠加：">
		        <Switch>
			        <span slot="open">是</span>
			        <span slot="close">否</span>
			    </Switch>
	        </FormItem> -->
			<FormItem label="适用会员等级：">
				<Select v-model="activity.memberRank" multiple>
			        <Option v-for="(rank,r) in memberRanks" :value="rank.id">{{ rank.name }}</Option>
			    </Select>
	        </FormItem>
			<FormItem label="活动说明：">
	            <Input v-model="activity.mark" type="textarea" :rows="5"></Input>
	        </FormItem>
			<FormItem>
	            <Button @click="submit" type="primary">保存</Button>
	            <Button @click="addToPlan" type="primary" class="icon-ml">保存并添加执行计划</Button>
                <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
	        </FormItem>
	    </Form>
	</Col>
	<Col span="10" offset="1">
		选择房间：
		<Tree :data="roomData"  @on-check-change="checkRoom" show-checkbox></Tree>
	</Col>
</Row>
</template>

<script>
export default{
	data () {
		return {
			typeList: [],
            roomData: [],
            memberRanks:[],
            activity:{
                name:null,
                fullCut:null,
                discount:null,
                memberRank:[],
                rooms:[],
                mark:null
            }
		}
	},
	mounted(){
	    var that=this;
        if(this.$route.params.activeId>0){
            this.host.post('merchantActivityInfo',{id:this.$route.params.activeId}).then(function(res){
                if(res.isSuccess()){
                    that.activity={
                        name:res.data().name,
                        discount:res.data().discount,
                        mark:res.data().mark,
                        rooms:res.data().rooms,
                        memberRank:res.data().memberRank,
                        fullCut:res.data().consumptionFull,
                    };
                }else{
                    this.$Notice.info({
                        title:'错误提示',
                        desc:res.error()
                    })
                }
            })
        }
        this.host.post('merchantRoomByTree',{activeId:this.$route.params.activeId}).then(function(res){
            if(res.isSuccess()){
                that.roomData=res.data();
            }else{
                this.$Notice.info({
                    title:'错误提示',
                    desc:res.error()
                })
            }
        })
        this.host.post('merchantMemberRankFoActivity').then(function(res){
            if(res.isSuccess()){
                that.memberRanks=res.data();
            }else{
                this.$Notice.info({
                    title:'错误提示',
                    desc:res.error()
                })
            }
        })
	},
	methods:{
		turnUrl:function(url,query){
            this.$router.push(url)
        },
		goBack(){
	        this.$router.go(-1);
	    },
	    checkRoom(tree){
            this.activity.rooms=[];
            for(var key in tree){
                if(tree[key].type==2){
                    this.activity.rooms.push(tree[key].id);
                }
            }
        },
        submit(){
            this.activity.id=this.$route.params.activeId;
            this.host.post('merchantActivityFullCutEdit',this.activity).then(function(res){
                if(res.isSuccess()){
                    this.$router.push('/admin/cutdown');
                }else{
                    this.$Notice.info({
                        title:'错误提示',
                        desc: res.error()
                    })
                }
            })
        },
        addToPlan(){
            this.activity.id=this.$route.params.activeId;
            this.host.post('merchantActivityFullCutEdit',this.activity).then(function(res){
                if(res.isSuccess()){
                    this.$router.push('/admin/planEdit/'+res.data().id);
                }else{
                    this.$Notice.info({
                        title:'错误提示',
                        desc: res.error()
                    })
                }
            })
        }
	}
}
</script>