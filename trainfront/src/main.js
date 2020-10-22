// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
// import Vue from "vue/dist/vue.common.js";
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import App from './App'
import router from './router'
import store from './store/index'
import Vuerouter from 'vue-router'
import VueResource from 'vue-resource'
import axios from 'axios'
// axios.defaults.baseURL ='/yii'
import baseConfig from '../config/dev.env'
// axios.interceptors.request.use(function (config) {
// //   if(config.url.indexOf("/translate")<0)
// //     config.url = baseConfig.apiBaseUrl +config.url
// //   return config
// // },function (error) {
// //   return Promise.reject(error)
// // });
// // axios.interceptors.response.use(function (response) {
// //   return response
// // },function (error) {
// //   return Promise.reject(error)
// // });
// 添加请求拦截器，在请求头加token
axios.interceptors.request.use(
  config=>{
    if(localStorage.getItem('Token'))
    {
      config.headers.Token = localStorage.getItem('Token')
    }
    return config;
  },
  error => {
    return Promise.reject(error)
  }
);
Vue.prototype.$http = axios
//
router.beforeEach((to,from,next)=>{
  let stoken = store.getters.getsToken
  let user=store.getters.getsName
  console.log(stoken)
  if (to.meta.isLogin==true) {
    if (!user) {
      next({path: '/login'})
    } else {
      return next()
    }
  }
    else{
     return next()
    }
})
Vue.use(VueResource)
Vue.use(Vuerouter)
// Vue.use(VueAxios,axios)//注册
Vue.use(ElementUI);
Vue.config.productionTip = false
/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})
