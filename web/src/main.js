import Vue from 'vue';
import iView from 'iview';
import VueRouter from 'vue-router';
import Routers from './router';
import Util from './libs/util';
import App from './app.vue';
import Resource from 'vue-resource';

Vue.use(VueRouter);
Vue.use(iView);
Vue.use(Resource);

import 'iview/dist/styles/iview.css';
import './styles/font-awesome.min.css';
import './styles/common.less';
import host from  './js/host';
import server from './server';
Vue.prototype.host=host(server);

// 路由配置
const RouterConfig = {
    mode: 'history',
    routes: Routers
};
const router = new VueRouter(RouterConfig);

router.beforeEach((to, from, next) => {
    document.cookie="pin=test;domain=hotel.com;";
    iView.LoadingBar.start();
    Util.title(to.meta.title);
    next();
});

router.afterEach((to, from, next) => {
    iView.LoadingBar.finish();
    window.scrollTo(0, 0);
});

let v=new Vue({
    el: '#app',
    router: router,
    render: h => h(App)
});
v.host.setApp(v);