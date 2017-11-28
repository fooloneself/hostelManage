function ResponseHandler(data,host) {
    if(!data.ok)throw new EventException('接口错误');
    this.__data=data=data.body;
    if(host.needLogin(this.errorCode())){
        host.getApp().$router.push(host.getLoginPath());
    }else if(!host.hasBindMch(this.errorCode())){
        host.getApp().$router.push(host.getPathForSetMch());
    }
    this.__host=host;
}
ResponseHandler.prototype={
    isSuccess:function () {
        return this.__data.success==1?true:false;
    },
    errorCode:function () {
        return this.__data.code;
    },
    error:function () {
        return this.__data.msg;
    },
    data:function () {
        return this.__data.results;
    },
    setApp:function (a) {
        this.__app=a;
        return this;
    }
}
function Response(promise,host) {
    this.__promise=promise;
    this.__host=host;
    this.then=function (func) {
        var $this=this;
        function response(data){
            var handler=new ResponseHandler(data,$this.__host);
            return func.call(this,handler);
        }
        this.__promise.then(response)
    }
}
function Host(config) {
    this.__config=config;
    this.__app;
}
Host.prototype={
    getUrl: function (name) {
        return this.__config.host+'/'+this.__config.interface[name];
    },
    post: function (name,param) {
        param=(param instanceof Object && !(param instanceof Array))?param:{};
        param.uid=this.getUid();
        param.token=this.getToken();
        var promise=this.__app.$http.post(this.getUrl(name),param);
        return new Response(promise,this);
    },
    setApp:function (app) {
        this.__app=app;
        return this;
    },
    getApp:function () {
        return this.__app;
    },
    getLoginPath:function () {
        return typeof this.__config.loginPath =='undefined' ? '/login':this.__config.loginPath;
    },
    getPathForSetMch:function () {
        return this.__config.setMerchantPath;
    },
    needLogin:function (code) {
        if(typeof this.__config.logoutCode =='object'){
            for(var key in this.__config.logoutCode){
                if(this.__config.logoutCode[key]==code)return true;
            }
        }
        return false;
    },
    hasBindMch:function(code){
        if(typeof this.__config.notBindMchCode =='object'){
            for(var key in this.__config.notBindMchCode){
                if(this.__config.notBindMchCode[key]==code)return false;
            }
        }
        return true;
    },
    setSession:function (uid,userName,token) {
        localStorage.setItem('uid',uid);
        localStorage.setItem('token',token);
        localStorage.setItem('userName',userName);
        return this;
    },
    getUid:function () {
        return localStorage.getItem('uid');
    },
    getToken:function () {
        return localStorage.getItem('token');
    },
    getUserName:function () {
        return localStorage.getItem('userName');
    },
    logout:function () {
        localStorage.clear();
    }
}
export default function (config) {
    return new Host(config);
};
