<template>
  <el-container>
    <el-header>
      <el-menu :unique-opened="true" :default-active="$route.path" @select="handleSelect" class="head" mode="horizontal">
        <el-menu-item index="1" @click="backHome">首页</el-menu-item>
        <el-menu-item index="teacherSelect">实习导师选择</el-menu-item>
        <el-submenu index="3">
          <template slot="title">教学工作实习</template>
          <el-menu-item index="listenRecord">听课记录表</el-menu-item>
          <el-menu-item index="teachDesign">教学实习教学设计</el-menu-item>
          <el-menu-item index="instructAssess">实习生授课评价表</el-menu-item>
          <el-menu-item index="diary">教学工作日记</el-menu-item>
        </el-submenu>
        <el-submenu index="4">
          <template slot="title">班主任工作实习</template>
          <el-menu-item index="someTemplate">班主任实习部分模板下载</el-menu-item>
          <el-menu-item index="workPlan">班主任工作计划</el-menu-item>
          <el-menu-item index="workDiary">班主任工作日记</el-menu-item>
          <el-menu-item index="specialEvent">班主任工作特殊事项记载</el-menu-item>
          <el-menu-item index="themeRecord">主题班会记录</el-menu-item>
          <el-menu-item index="eventRecord">班级活动记录</el-menu-item>
          <el-menu-item index="personalInstruct">个别教育记录</el-menu-item>
          <el-menu-item index="parentMeeting">家长会记录</el-menu-item>
        </el-submenu>
        <el-menu-item index="researchReport">教育研究报告</el-menu-item>
        <el-submenu index="6">
          <template slot="title">教育研习</template>
          <el-menu-item index="6-1">教学工作研习记录</el-menu-item>
          <el-menu-item index="6-2">班主任工作研习记录</el-menu-item>
          <el-menu-item index="6-3">教育研究研习记录</el-menu-item>
          <el-menu-item index="6-4">教育研究总结报告</el-menu-item>
        </el-submenu>
        <el-submenu index="7">
          <template slot="title">实习资源管理</template>
          <el-menu-item index="recordManage">实习记录管理</el-menu-item>
          <el-menu-item index="resourceManage">相关教学资源</el-menu-item>
        </el-submenu>
        <el-menu-item index="8">总结与评定</el-menu-item>
        <el-menu-item index="9">实习反馈</el-menu-item>
        <el-menu-item index="my">个人中心</el-menu-item>
        <el-menu-item index="11">消息中心</el-menu-item>
        <el-menu-item index="12" @click="logout()">退出登录</el-menu-item>
      </el-menu>
    </el-header>
    <el-main style="background-color: white">
      <router-view v-if="isShow"></router-view>
    </el-main>
  </el-container>
</template>

<script>
    export default {
        name: "index",
        data(){
            return{
                isShow:true,
                username:'',
                password:''
            }
        },
        methods:{
            handleSelect(path){
                this.isShow=false
                this.$nextTick(()=>{
                    this.isShow=true
                })
                this.$router.push(path)
            },
            backHome(){
                //回到首页
                this.$router.push({path:'/home/main'})
            },
            //退出登录
            logout(){
                this.$http.post('/yii/home/index/logout',{username:this.username})
                    .then((res)=>{
                        console.log(res.data)
                        this.$store.dispatch('logout',res.data.data)
                        this.$store.dispatch('slogout',res.data.data)
                        this.$router.push({path:'/login'})//重新定向到登录界面
                        alert(res.data.message)
                    },(err)=>{
                        console.log(err)
                    })
            }
        },
        created() {
            //获取当前用户的用户名和密码
            this.username=this.$store.getters.getsName
            this.password=this.$store.getters.getsPwd
        }
    }
</script>

<style scoped>
  el-header, el-footer {
    background-color: #B3C0D1;
    color: #333;
    text-align: center;
    line-height: 60px;
  }
  el-main {
    background-color: #E9EEF3;
    color: #333;
    text-align: center;
    line-height: 160px;
  }
  .head{
    background-color:#05607c;
    text-color:#fff;
    active-text-color:#ffd04b;
  }
</style>
