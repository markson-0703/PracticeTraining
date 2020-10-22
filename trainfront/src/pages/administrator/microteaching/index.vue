<template>
    <el-container>
      <el-header>
        <el-menu :unique-opened="true" :default-active="$route.path" @select="handleSelect" class="head" mode="horizontal">
          <el-menu-item index="1" @click="backHome">首页</el-menu-item>
          <el-menu-item index="user">用户管理</el-menu-item>
          <el-menu-item index="3">师生信息管理</el-menu-item>
          <el-menu-item index="4">微格训练安排</el-menu-item>
          <el-submenu index="5">
            <template slot="title">微格训练资源管理</template>
            <el-submenu index="5-1">
              <template slot="title">微格训练记录</template>
              <el-menu-item index="5-1-1">技能训练</el-menu-item>
              <el-menu-item index="5-1-2">微格视频</el-menu-item>
            </el-submenu>
            <el-submenu index="5-2">
              <template slot="title">教师指导记录</template>
              <el-menu-item index="5-2-1">技能训练指导</el-menu-item>
              <el-menu-item index="5-2-2">模拟授课指导</el-menu-item>
              <el-menu-item index="5-2-3">教学测评指导</el-menu-item>
            </el-submenu>
            <el-submenu index="5-3">
              <template slot="title">校外教师指导记录</template>
              <el-menu-item index="5-3-1">试讲与授课指导</el-menu-item>
            </el-submenu>
          </el-submenu>
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
                name:''
            }
        },
        methods:{
            handleSelect (path) {
                this.$router.push(path)
            },
            backHome(){
                this.$router.push({path:'/home/main'})
            }
        },
        created() {
            console.log(this.$store.getters.getsName)
            this.name=this.$store.getters.getsName
        }
    }
</script>

<style  scoped="scoped" type="text/css">

</style>
