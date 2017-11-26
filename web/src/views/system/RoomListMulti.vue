<template>
<Row>
    <Col span="10">
        <Form label-position="top">
            <FormItem label="房屋类型：">
                <Select placeholder="请选择房屋类型">
                    <Option value="1">类型一</Option>
                    <Option value="2">类型二</Option>
                    <Option value="3">类型三</Option>
                </Select>
            </FormItem>
            <FormItem label="房间号：">
                <Row :gutter="8">
                    <Col span="8" v-for="i in number" class="mt">
                        <Input type="text" placeholder="请输入房间号">
                            <Button slot="append" @click="deleteNumber">删除</Button>
                        </Input>
                    </Col>
                    <Col span="8" class="mt">
                        <Button @click="addNumber" type="dashed" long>
                            <i class="fa fa-plus icon-mr" aria-hidden="true"></i>新增一个房间
                        </Button>
                    </Col>
                </Row>
            </FormItem>
            <FormItem>
                <Button type="primary">保存</Button>
                <Button type="ghost" @click="goBack" class="icon-ml">取消</Button>
            </FormItem>
        </Form>
    </Col>
</Row>
</template>

<script>
    export default{
    	data () {
    		return {
                number: 5
    		}
    	},
        methods:{
            goBack (){
                this.$router.go(-1);
            },
            addNumber (){
                this.number++;
            },
            deleteNumber (){
                if(this.number>1) this.number--;
                else 
                    this.$Notice.info({
                        title:'提示',
                        desc:'添加房间至少需要一件房间！'
                    });
            }
        }
    }
</script>