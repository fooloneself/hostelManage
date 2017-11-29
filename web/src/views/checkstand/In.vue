<style scoped>
.room-pick{
	cursor: pointer;
	height: 100px;
	.room{
		height: 84px;
		background: #FFF;
		border-radius: 5px;
		border: 1px solid #dddee1;
		text-align: center;
		.type{
			width: 100%;
			height: 28px;
			line-height: 28px;
			font-size: 12px;
		}
		.number{
			width: 100%;
			height: 28px;
			line-height: 28px;
			font-size: 28px;
			font-weight: bolder;
		}
		.name{
			width: 100%;
			height: 28px;
			line-height: 28px;
			font-size: 12px;
		}
		&:hover{
			background: #dddee1;
		}
		&.room-in{
			background: #16A085;
			color: #FFFFFF;
			border: none;
		}
		&.room-order{
			background: #E67E22;
			color: #FFFFFF;
			border: none;
		}
		&.room-clock{
			background: #2980B9;
			color: #FFFFFF;
			border: none;
		}
		&.room-dirty{
			background: #7F8C8D;
			color: #FFFFFF;
			border: none;
		}
		&.room-lock{
			background: #BDC3C7;
			color: #FFFFFF;
			border: none;
		}
	}
}
</style>
<template>
<div>
	<Spin size="large" fix v-if="spinShow"></Spin>
	<Row>
		<Col span="12">
			<ButtonGroup size="small">
				<Button type="text"><Icon type="record" style="color:#16A085"></Icon><span class="icon-ml">全天</span></Button>
				<Button type="text"><Icon type="record" style="color:#2980B9"></Icon><span class="icon-ml">钟点</span></Button>
				<Button type="text"><Icon type="record" style="color:#E67E22"></Icon><span class="icon-ml">预订</span></Button>
				<Button type="text"><Icon type="record" style="color:#7F8C8D"></Icon><span class="icon-ml">脏房</span></Button>
				<Button type="text"><Icon type="record" style="color:#BDC3C7"></Icon><span class="icon-ml">锁房</span></Button>
			</ButtonGroup>
		</Col>
		<Col span="12">
			<div class="fr">
				<Select v-model="filter.status" placeholder="房屋状态" class="search-input">
	                <Option value="-1">全部</Option>
	                <Option value="0">空房</Option>
	                <Option value="4">全天</Option>
	                <Option value="">钟点</Option>
	                <Option value="3">预订</Option>
	                <Option value="1">脏房</Option>
	                <Option value="2">锁房</Option>
	            </Select>
	            <Select v-model="filter.type" placeholder="房屋类型" class="search-input">
	                <Option value="0">全部</Option>
	                <Option v-for="type in types" :value="type.id">{{type.name}}</Option>
	            </Select>
	            <Select v-model="filter.guestId" placeholder="客户名称" class="search-input">
	                <Option value="0">全部</Option>
	                <Option v-for="(guest,g) in guests" :value="guest.id">{{guest.name}}</Option>
	            </Select>
	            <Button @click="refresh" type="primary">查询</Button>
            </div>
		</Col>
	</Row>
	<div class="mb"></div>
	<Row :gutter="16">
		<Col v-for="room in rooms" span="2" class="room-pick">
			<div class="room" :class="getClass(room.roomStatus)" @click="roomClick(room)">
				<div class="type">{{room.typeName}}</div>
				<div class="number">{{room.roomNumber}}</div>
				<div class="name">{{room.guestName}}</div>
			</div>
		</Col>
	</Row>
	<Modal v-model="modalShow" title="提示">
		<p>请确认是否将该房间从脏房置为空房？</p>
	</Modal>
</div>
</template>
<script>
export default{
	data (){
		return {
			modalShow: false,
			spinShow: true,
			weekday: ['二','三','四','五','六','日','一','二','三','四'],
			rooms: [],
			types: [],
			guests:[],
			filter:{
			    status: -1,
			    type: 0,
			    guestId:0
			}
		}
	},
	mounted(){
	    var that=this;
	    this.host.post('checkstandRoomFilter').then(function(res){
            if(res.isSuccess()){
                that.types=res.data().types;
            }else{
                that.$Notice.info({
                    title: '提示',
                    desc: res.error()
                })
            }
	    })
	    this.host.post('merchantOrderGuest').then(function(res){
            if(res.isSuccess()){
                that.guests=res.data();
            }else{
                that.$Notice.info({
                    title: '提示',
                    desc: res.error()
                })
            }
        })
	    this.refresh();
	},
	methods:{
		turnUrl:function(name){
            this.$router.push(name);
        },
        refresh (){
            var that=this;
            this.loading();
            this.host.post('checkstandRoom',this.filter).then(function(res){
                that.loaded();
                if(res.isSuccess()){
                    that.rooms=res.data();
                }else{
                    this.$Notice.info({
                        title: '提示',
                        desc: res.error()
                    })
                }
            })
        },
        roomClick (room){
            switch(room.roomStatus){
                case 0:
                    this.$router.push('/admin/checkstandEdit/'+room.roomId+'/'+room.orderId);
                    break;
                case 1:
                    this.modalShow=true;
                    break;
                default:
                    this.$router.push('/admin/checkstandView/'+room.roomId+'/'+room.orderId);
            }
        },
        getClass (status){
            switch(status){
                case 1:
                    return 'room-dirty';
                    break;
                case 2:
                    return 'room-lock';
                    break;
                case 3:
                    return 'room-order';
                    break;
                case 4:
                    return 'room-in';
                    break;
                case 0:
                default:
                    return '';
            }
        },
        loading (){
            this.spinShow=true;
        },
        loaded (){
            this.spinShow=false;
        }
	}
}
</script>