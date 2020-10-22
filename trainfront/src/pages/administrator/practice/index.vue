<template>
    <el-container>
      <el-header>
        <el-menu :unique-opened="true" :default-active="$route.path" @select="handleSelect" class="head" mode="horizontal">
          <el-menu-item index="1" @click="backHome">首页</el-menu-item>
          <el-menu-item index="user">用户管理</el-menu-item>
          <el-menu-item index="3">信息管理</el-menu-item>
          <el-submenu index="4">
            <template slot="title">实习信息管理</template>
            <el-menu-item index="4-1">校内导师安排</el-menu-item>
            <el-menu-item index="4-2">校外导师安排</el-menu-item>
          </el-submenu>
          <el-submenu index="5">
            <template slot="title">实习与研习资源管理</template>
            <el-submenu index="5-1">
              <template slot="title">实践工作记录</template>
              <el-menu-item index="5-1-1">教学观摩</el-menu-item>
              <el-menu-item index="5-1-2">试讲与授课</el-menu-item>
              <el-menu-item index="5-1-3">班主任工作</el-menu-item>
              <el-menu-item index="5-1-4">教育研究</el-menu-item>
              <el-menu-item index="5-1-5">研习记录</el-menu-item>
              <el-menu-item index="5-1-6">教学工作周记</el-menu-item>
            </el-submenu>
            <el-submenu index="5-2">
              <template slot="title">校内教师指导记录</template>
              <el-menu-item index="5-2-1">试讲与授课指导</el-menu-item>
              <el-menu-item index="5-2-2">班主任工作指导</el-menu-item>
              <el-menu-item index="5-2-3">教育研究指导</el-menu-item>
              <el-menu-item index="5-2-4">试讲与授课指导</el-menu-item>
            </el-submenu>
            <el-submenu index="5-3">
              <template slot="title">校外教师指导记录</template>
              <el-menu-item index="5-3-1">试讲与授课指导</el-menu-item>
            </el-submenu>
          </el-submenu>
          <el-submenu index="6">
            <template slot="title">实习与研习反馈</template>
            <el-submenu index="6-1">
              <template slot="title">学生的个人评价</template>
              <el-menu-item index="6-1-1">自我评价</el-menu-item>
              <el-menu-item index="6-1-2">小组评价</el-menu-item>
            </el-submenu>
            <el-menu-item index="6-2">教师总结与评价</el-menu-item>
            <el-menu-item index="6-3">成绩鉴定</el-menu-item>
          </el-submenu>
          <el-menu-item index="my">个人中心</el-menu-item>
          <el-menu-item index="8" @click="logout()">退出登录</el-menu-item>
        </el-menu>
      </el-header>
      <el-main style="background-color: white">
        <router-view></router-view><!--这是路由出口-->
      </el-main>
    </el-container>
</template>

<script>
    export default {
        name: "index",
        data(){
            return{
                username:''
            }
        },
        methods:{
            backHome(){
                this.$router.push({path:'/home/main'})
            },
            handleSelect (path) {
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
            console.log(this.$store.getters.getsName)
            this.username=this.$store.getters.getsName
        }
    }
</script>

<style scoped="scoped" type="text/css">

</style>
