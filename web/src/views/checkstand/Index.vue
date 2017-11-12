<style scoped>
.pick-area{
	position: absolute;
	top: 60px;
	right: 0;
	left: 0;
	bottom: 0;
	background: #FFF;
}
.date-pick{
	background: url('/src/images/bj.png');
	padding-top: 10px;
	padding-bottom: 10px;
    min-width: 1208px;
    font-weight: bolder;
    .current{
    	color: #000;
    }
}
.room-pick{
	cursor: pointer;
	height: 100px;
	.room{
		height: 84px;
		background: #FFF;
		border-radius: 5px;
		border: 1px solid #dddee1;
		text-align: center;
		&:hover{
			background: #dddee1;
		}
		.type{
			width: 100%;
			height: 32px;
			line-height: 42px;
			font-size: 14px;
		}
		.number{
			width: 100%;
			height: 52px;
			font-size: 28px;
			font-weight: bolder;
		}
		&.room-in{
			background: #49D0B5;
			color: #FFFFFF;
			border: none;
		}
		&.room-order{
			background: #5688D2;
			color: #FFFFFF;
			border: none;
		}
		&.room-order-multi{
			background: #FFFFFF;
			color: #5688D2;
			border: 1px solid #5688D2;
		}
		&.room-clock{
			background: #FD9A59;
			color: #FFFFFF;
			border: none;
		}
		&.room-dirty{
			background: #CCCCCC;
			color: #FFFFFF;
			border: none;
		}
		&.room-lock{
			background: #EEEEEE;
			color: #FFFFFF;
			border: none;
		}
	}
}
.current-status{
	border-bottom: 2px solid #16A085;
}
</style>
<template>
<div class="pick-area">
	<Spin size="large" fix v-if="spinShow"></Spin>
	<Row class="date-pick">
		<div class="container-body mlr">
			<Col span="2" class="tc">
				<Icon type="calendar" size="34"></Icon>
			</Col>
			<Col span="2" class="current tc">
				09-05 星期一<br/>剩余15间
			</Col>
			<Col v-for="week in weekday" span="2" class="tc">
				09-05 星期{{week}}<br/>剩余15间
			</Col>
		</div>
	</Row>
	<div class="mb"></div>
	<div class="container-body mlr">
		<Row>
			<Col span="12">
				<ButtonGroup size="small">
					<Button @click="selectStatus(-1)" class="current-status" type="text">全部状态</Button>
					<Button @click="selectStatus(0)" type="text"><Icon type="record" style="color:#666666"></Icon><span class="icon-ml">空房</span></Button>
					<Button @click="selectStatus(4)" type="text"><Icon type="record" style="color:#49D0B5"></Icon><span class="icon-ml">入住</span></Button>
					<Button @click="selectStatus(3)" type="text"><Icon type="record" style="color:#5688D2"></Icon><span class="icon-ml">预订</span></Button>
					<Button @click="selectStatus(1)" type="text"><Icon type="record" style="color:#CCCCCC"></Icon><span class="icon-ml">脏房</span></Button>
					<Button @click="selectStatus(2)" type="text"><Icon type="record" style="color:#EEEEEE"></Icon><span class="icon-ml">锁房</span></Button>
				</ButtonGroup>
			</Col>
			<Col span="12" class="tr">
				<ButtonGroup size="small">
					<Button @click="selectType(0)" class="current-status" type="text">全部房型</Button>
					<Button v-for="type in types" @click="selectType(type.id)" type="text">{{type.name}}</Button>
				</ButtonGroup>
			</Col>
		</Row>
		<div class="mb"></div>
		<Row :gutter="16">
			<Col span="2" class="room-pick">
				<div class="room room-order-multi">
					<div class="type">批量预定</div>
					<div class="number"><i class="fa fa-bed" aria-hidden="true"></i></div>
				</div>
			</Col>
			<Col v-for="room in rooms" span="2" class="room-pick">
				<div class="room" :class="getClass(room.status)" @click="roomClick(room.id,room.status)">
					<div class="type">{{room.typeName}}</div>
					<div class="number">{{room.number}}</div>
				</div>
			</Col>
		</Row>
	</div>
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
			filter:{
			    time: 0,
			    status: -1,
			    type: 0
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
	    this.refresh();
	},
	methods:{
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
        selectType (type){
            this.filter.type=type;
            this.refresh();
        },
        selectStatus(status){
            this.filter.status=status;
            this.refresh();
        },
        roomClick (roomId,status){
            switch(status){
                case 0:
                    this.$router.push('/admin/checkstandEdit/'+roomId);
                    break;
                case 1:
                    this.modalShow=true;
                    break;
                default:
                    this.$router.push('/admin/checkstandView/'+roomId);
            }
        },
        getClass (status){
            switch(status){
                case 1:
                    return 'room-dirty';
                    break;
                case 2:
                    return 'room-clock';
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