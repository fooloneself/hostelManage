<style scoped>
.table{
	background: #dddee1;
	text-align: center;
	width: 100%;
	thead{
		td{
			background: #ECF0F1;
			padding: 5px 0;
			line-height: 20px;
			color: #7F8C8D;
			&.calendar{
				font-size: 16px;
				font-weight: bolder;
			}
		}
	}
	tbody {
		tr:hover{
			td{
				background: #f3f3f3;
			}
		}
		td{
			background: #ffffff;
			height: 40px;
			&.column{
				background: #f3f3f3;
				&:hover{
					background: #95A5A6;
				}
			}
			.ordered,.locked,.ordering{
				width: 100%;
				height: 100%;
				color: #FFFFFF;
				line-height: 40px;
				font-size: 12px;
			}
			.ordered{
				background: #E67E22;
				line-height: 20px;
				cursor: pointer;
			}
			.locked{
				background: #BDC3C7;
			}
			.ordering{
				background: #F39C12;
				cursor: pointer;
			}
		}
	}
}
</style>
<template>
<div>
	<Spin size="large" fix v-if="spinShow"></Spin>
	<Row>
		<Col span="12">
			<Badge :count="ordering_number">
				<Button type="warning" @click="funcDoOrder">
					预订登记结算
					<Icon type="chevron-right" class="icon-ml"></Icon>
				</Button>
			</Badge>
		</Col>
		<Col span="12">
			<div class="fr">
				<Select placeholder="房屋类型" class="search-input">
				    <Option value="0">全部</Option>
				    <Option value="1">房屋类型一</Option>
				    <Option value="2">房屋类型二</Option>
				    <Option value="3">房屋类型三</Option>
				</Select>
				<DatePicker type="date" placeholder="选择日期" class="search-input"></DatePicker>
				<Select placeholder="客户名称" class="search-input">
				    <Option value="">全部</Option>
				    <Option value="张三">张三</Option>
				    <Option value="李四">李四</Option>
				    <Option value="王五">王五</Option>
				    <Option value="刘六">刘六</Option>
				</Select>
	            <Button type="primary">查询</Button>
            </div>
		</Col>
	</Row>
	<div class="mb"></div>
    <table cellpadding="0" cellspacing="1" class="table">
		<thead>
			<tr>
				<td colspan="2" class="calendar">2017-11-24</td>
				<td v-for="i in 16">星期一<br/>11-{{i}}</td>
			</tr>
		</thead>
		<tbody>
			<tr v-for="i in 40">
				<td>豪华大床房</td>
				<td width="50">{{i}}</td>
				<template v-for="j in 16">
					<td v-if="i*j==50" @mouseenter="enter(j)"  @mouseleave="leave" @click="funcOrdered(j)">
						<div class="ordered">预订<br/>邓世勇</div>
					</td>
					<td v-else-if="i*j==100" @mouseenter="enter(j)"  @mouseleave="leave">
						<div class="locked">锁房</div>
					</td>
					<td v-else :class="j==column?'column':''" @mouseenter="enter(j)"  @mouseleave="leave" @click="funcOrdering(i,j)">
						<div class="ordering" v-if="ordering.indexOf(i+'-'+j)!=-1">选房</div>
					</td>
				</template>
			</tr>
		</tbody>
    </table>
</div>
</template>
<script>
export default{
	data (){
		return {
			spinShow: false,
			column:'',
        	ordering:[],
        	ordering_number:0
		}
	},
	methods:{
		funcDoOrder(){
			if(this.ordering_number>0)
	        	this.$router.push('/admin/checkstandOrderEdit/0');
	        else
				this.$Notice.info({
                    title:'提示',
                    desc:'您还未选择任何房间，请选择房间后再操作。'
                });
	    },
	    enter(index){
    		this.column = index;
    	},
    	leave(){
    		this.column = '';
    	},
    	funcOrdering(i,j){
    		var t = this.ordering.indexOf(i+'-'+j)
    		if(t==-1){
    			this.ordering.push(i+'-'+j);
    			this.ordering_number++;
    		} else {
    			this.ordering.splice(t,1);
    			this.ordering_number--;
    		}
    	},
    	funcOrdered(id){
    		this.$router.push('/admin/checkstandOrderView/'+id);
    	}
	}
}
</script>