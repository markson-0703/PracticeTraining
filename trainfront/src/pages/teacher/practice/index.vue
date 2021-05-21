<template>
  <el-container>
    <el-header>
      <el-menu :unique-opened="true" :default-active="$route.path" @select="handleSelect" class="head" mode="horizontal">
        <el-menu-item index="1" @click="backHome">首页</el-menu-item>
        <el-menu-item index="teamManage">学生信息管理</el-menu-item>
        <el-menu-item index="practicePlan">教育实习工作记录</el-menu-item>
        <el-menu-item index="7">教育实习总结</el-menu-item>
        <el-submenu index="8">
          <template slot="title">教育研习</template>
          <el-menu-item index="8-1">研习指导记录</el-menu-item>
          <el-menu-item index="8-2">实研习指导总结</el-menu-item>
        </el-submenu>
        <el-submenu index="9">
          <template slot="title">实习资源查看</template>
          <el-menu-item index="9-1">实习相关记录</el-menu-item>
          <el-menu-item index="9-2">学生实习资源</el-menu-item>
        </el-submenu>
        <el-menu-item index="10">实习评定</el-menu-item>
        <el-menu-item index="11">实习成绩鉴定</el-menu-item>
        <el-menu-item index="my">个人中心</el-menu-item>
        <el-menu-item index="13">消息中心</el-menu-item>
        <el-menu-item  @click="logout()">退出登录</el-menu-item>
      </el-menu>
    </el-header>
    <el-main style="background-color: white">
      <router-view v-if="isShow"></router-view><!--这是路由出口-->
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
