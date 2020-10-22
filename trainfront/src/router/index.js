import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import Register from '../pages/register'
import Refine from '../pages/refine'
import Forget from '../pages/forget'
import Login from '../pages/login'
import Index from '../pages/index.vue'
import Header from "../components/Header";
import Footer from "../components/Footer";
import Main from "../pages/home/main.vue";
import ProbationIndex from "../pages/administrator/probation/index.vue";
import PracticeIndex from "../pages/administrator/practice/index";
import ProbationIndex1 from "../pages/student/probation/index";
import HomePage from "../pages/administrator/probation/homepage";
import My from "../pages/administrator/probation/my";
import My1 from "../pages/student/probation/my";
import User from "../pages/administrator/probation/user"
import Member from "../pages/administrator/probation/member";
import SiteArrange from "../pages/administrator/probation/siteArrange";
import TutorSelect from "../pages/student/probation/tutorSelect"
Vue.use(Router)
const  router = new Router({
  mode:'history',
  routes: [
    {
      //测试
      path: '/',
      name: 'Index',
      meta:{
        isLogin:true,//需要登录
      },
      component: Index
    },
    {
      //注册
      path: '/register',
      name: 'Register',
      meta:{
        isLogin:false,
      },
      component: Register
    },
    {
      //信息完善界面
      path:'/refine',
      name:'Refine',
      meta:{
        isLogin:false,
      },
      component:Refine
    },
    {
      //忘记密码
      path:'/forget',
      name:'Forget',
      meta:{
        isLogin:false,
      },
      component:Forget
    },
    {
      //登录
      path:'/login',
      name:'Login',
      meta:{
        isLogin:false,
      },
      component:Login
    },
    {
      //头部
      path:'/header',
      name:'Header',
      meta:{
        isLogin:false,
      },
      component:Header
    },
    {
      //底部
      path:'/footer',
      name:'Footer',
      mata:{
        isLogin:false,
      },
      component:Footer
    },
    {
      //主页
      path:'/home/main',
      name:'Main',
      mata:{
        isLogin:true,
      },
      component:Main
    },
    {
      //管理员见习首页
      path:'/administrator/probation/index',
      name:'ProbationIndex',
      meta:{
        isLogin:true,
      },
      component:ProbationIndex,
      children:[
        {
          path: '/administrator/probation/homepage',
          name: 'HomePage',
          component: HomePage
        },
        {
          path: '/administrator/probation/my',
          name: 'My',
          component: My
        },
        {
          path: '/administrator/probation/user',
          name: 'User',
          component: User
        },
        {
          path: '/administrator/probation/member',
          name: 'Member',
          component: Member
        },
        {
          path: '/administrator/probation/siteArrange',
          name: 'SiteArrange',
          component: SiteArrange
        }
      ]
    },
    {
      //管理员实习首页
      path:'/administrator/practice/index',
      name:'PracticeIndex',
      meta:{
        isLogin:true,
      },
      component:PracticeIndex,
      children:[
        {
          path: '/administrator/probation/my',
          name: 'My',
          component: My
        }
      ]
    },
    {
      //学生见习首页
      path:'/student/probation/index',
      name:'ProbationIndex1',
      meta:{
        isLogin:true,
      },
      component:ProbationIndex1,
      children:[
        {
          path: '/student/probation/my',
          name: 'My1',
          component: My1
        },
        {
          path: '/student/probation/tutorSelect',
          name: 'TutorSelect',
          component: TutorSelect
        }
      ]
    }
  ]
})

export default router;
const originalPush = Router.prototype.push
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => err)
}
