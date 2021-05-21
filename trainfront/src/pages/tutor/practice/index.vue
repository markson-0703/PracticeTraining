<template>
  <el-container>
    <el-header>
      <el-menu :unique-opened="true" :default-active="$route.path" @select="handleSelect" class="head" mode="horizontal">
        <el-menu-item index="1" @click="backHome">首页</el-menu-item>
        <el-menu-item index="memberData">学生信息管理</el-menu-item>
<!--        <el-submenu index="3">-->
<!--          <template slot="title">实习工作指导记录</template>-->
<!--          <el-menu-item index="3-1">实习教学设计指导</el-menu-item>-->
<!--          <el-menu-item index="3-2">实习授课指导</el-menu-item>-->
<!--          <el-menu-item index="3-3">班级工作计划指导</el-menu-item>-->
<!--        </el-submenu>-->
        <el-menu-item index="Allguidance">实习工作指导记录</el-menu-item>
        <el-menu-item index="4">实习研习评定</el-menu-item>
        <el-menu-item index="my">个人中心</el-menu-item>
        <el-menu-item index="6">消息中心</el-menu-item>
        <el-menu-item  @click="logout()">退出登录</el-menu-item>
      </el-menu>
    </el-header>
    <el-main style="background-color: white">
      <router-view v-if="showView"></router-view><!--这是路由出口-->
    </el-main>
  </el-container>
</template>

<script>
    export default {
        name: "index",
        data(){
            return{
                showView:true,
                username:'',
                password:''
            }
        },
        methods:{
            handleSelect (path) {
                this.showView=false
                this.$nextTick(()=>{
                    this.showView=true
                })
                this.$router.push(path)
            },
            backHome(){
                this.$router.push({path:'/home/main'})
            },
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
            this.username=this.$store.getters.getsName
            this.password=this.$store.getters.getsPwd
        }
    }
</script>

<style scoped="scoped" type="text/css">
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
