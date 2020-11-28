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
import ProbationIndex from "../pages/administrator/probation/index.vue";//管理员见习首页
import PracticeIndex from "../pages/administrator/practice/index";
import ProbationIndex1 from "../pages/student/probation/index";//学生见习首页
import HomePage from "../pages/administrator/probation/homepage";
import My from "../pages/administrator/probation/my";
import My1 from "../pages/student/probation/my";
import My2 from "../pages/teacher/probation/my";
import User from "../pages/administrator/probation/user"
import Member from "../pages/administrator/probation/member";
import SiteArrange from "../pages/administrator/probation/siteArrange";
import TutorSelect from "../pages/student/probation/tutorSelect";
import ProbationIndex2 from "../pages/teacher/probation/index";//校内教师见习首页
import StudentManage from "../pages/teacher/probation/studentManage";
import Process from "../pages/student/probation/process";
import ActivityArrange from"../pages/administrator/probation/activityArrange";
import ProbationRecord from"../pages/student/probation/probationRecord";
import ProbationRecord1 from"../pages/student/probation/probationRecord1";
import ProbationRecord2 from"../pages/student/probation/probationRecord2";
import ProbationRecord3 from"../pages/student/probation/probationRecord3";
import ProbationRecord4 from"../pages/student/probation/probationRecord4";
import ProbationRecord5 from"../pages/student/probation/probationRecord5";
import ResourceManage from "../pages/student/probation/resourceManage";
import RecordManage from "../pages/student/probation/recordManage";
import MyConclusion from "../pages/student/probation/myConclusion";
import DirectionRecord from "../pages/teacher/probation/directionRecord";
import Record from "../pages/teacher/probation/record";
import Resource from "../pages/teacher/probation/resource";
import Instruction from "../pages/teacher/probation/instruction";
import StudentFile from "../pages/administrator/probation/studentFile";
import TeacherFile from "../pages/administrator/probation/teacherFile";

Vue.use(Router)
const  router = new Router({
  mode:'history',
  scrollBehavior(to,from,saveTop){
       if(saveTop){
         return saveTop
       }else{
         return{x:0,y:0}
       }
  },
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
        },
        {
          path: '/administrator/probation/activityArrange',
          name: 'ActivityArrange',
          component: ActivityArrange
        },
        {
          path: '/administrator/probation/studentFile',
          name: 'StudentFile',
          component: StudentFile
        },
        {
          path: '/administrator/probation/teacherFile',
          name: 'TeacherFile',
          component: TeacherFile
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
      component:PracticeIndex
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
        },
        {
          path:'/student/probation/process',
          name:'Process',
          component: Process
        },
        {
          path:'/student/probation/resourceManage',
          name:'ResourceManage',
          component: ResourceManage
        },
        {
          path:'/student/probation/probationRecord',
          name:'ProbationRecord',
          component: ProbationRecord
        },
        {
          path:'/student/probation/probationRecord1',
          name:'ProbationRecord1',
          component: ProbationRecord1
        },
        {
          path:'/student/probation/probationRecord2',
          name:'ProbationRecord2',
          component: ProbationRecord2
        },
        {
          path:'/student/probation/probationRecord3',
          name:'ProbationRecord3',
          component: ProbationRecord3
        },
        {
          path:'/student/probation/probationRecord4',
          name:'ProbationRecord4',
          component: ProbationRecord4
        },
        {
          path:'/student/probation/probationRecord5',
          name:'ProbationRecord5',
          component: ProbationRecord5
        },
        {
          path:'/student/probation/recordManage',
          name:'RecordManage',
          component: RecordManage
        },
        {
          path:'/student/probation/myConclusion',
          name:'MyConclusion',
          component: MyConclusion
        }
      ]
    },
    {
      //校内教师见习首页
      path:'/teacher/probation/index',
      name:'ProbationIndex2',
      meta:{
        isLogin:true,
      },
      component:ProbationIndex2,
      children:[
        {
          path: '/teacher/probation/my',
          name: 'My2',
          component: My2
        },
        {
          path: '/teacher/probation/studentManage',
          name: 'StudentManage',
          component: StudentManage
        },
        {
          path: '/teacher/probation/directionRecord',
          name: 'DirectionRecord',
          component: DirectionRecord
        },
        {
          path: '/teacher/probation/record',
          name: 'Record',
          component:Record
        },
        {
          path: '/teacher/probation/instruction',
          name: 'Instruction',
          component:Instruction
        },
        {
          path: '/teacher/probation/resource',
          name: 'Resource',
          component:Resource
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
