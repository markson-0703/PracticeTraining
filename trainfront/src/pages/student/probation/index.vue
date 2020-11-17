<template>
  <el-container>
    <el-header>
      <el-menu :unique-opened="true" :default-active="$route.path" @select="handleSelect" class="head" mode="horizontal">
        <el-menu-item index="1" @click="backHome">首页</el-menu-item>
        <el-menu-item index="tutorSelect">见习导师选择</el-menu-item>
<!--        <el-menu-item index="3">教学实践记录</el-menu-item>-->
        <el-submenu index="3">
          <template slot="title">教学实践记录</template>
          <el-menu-item index="process">见习历程</el-menu-item>
          <el-menu-item index="probationRecord">见习记录</el-menu-item>
        </el-submenu>
        <el-submenu index="4">
          <template slot="title">见习资源管理</template>
          <el-menu-item index="recordManage">见习记录管理</el-menu-item>
          <el-menu-item index="resourceManage">教学资源管理</el-menu-item>
        </el-submenu>
        <el-menu-item index="5">见习工作的评价</el-menu-item>
        <el-menu-item index="6">见习反馈</el-menu-item>
        <el-menu-item index="my">个人中心</el-menu-item>
        <el-menu-item index="8" @click="logout()">退出登录</el-menu-item>
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
            backHome(){
                this.$router.push({path:'/home/main'})
            },
            handleSelect (path) {
                this.showView=false
                this.$nextTick(()=>{
                    this.showView=true
                })
                this.$router.push(path)
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
