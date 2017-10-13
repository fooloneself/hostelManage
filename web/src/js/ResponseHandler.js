export default function (data) {
    if(!data.ok)throw new EventException('接口错误');
    data=data.body;
    var res={
        isSuccess:function () {
            return data.success==1?true:false;
        },
        errorCode:function () {
            return data.code;
        },
        error:function () {
            return data.msg;
        },
        data:function () {
            return data.results;
        },
        setApp:function (a) {
            app=a;
            return this;
        }
    };
    if(res.errorCode()==100010 || res.errorCode()==100011 || res.errorCode()==100004){
        this.$router.push('/login');
    }
    return res;
}