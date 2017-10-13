function Host(config) {
    this.__config=config;
}
Host.prototype={
    getUrl: function (name) {
        return this.__config.host+'/'+this.__config.interface[name];
    },
    post: function (name,param) {
        return this.__app.$http.post(this.getUrl(name),param);
    },
    setApp:function (app) {
        this.__app=app;
        return this;
    }
}
export default function (config) {
    return new Host(config);
};
