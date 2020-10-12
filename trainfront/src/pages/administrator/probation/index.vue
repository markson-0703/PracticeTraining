<template>
  <el-container>
    <el-header>
      <el-menu :default-active="activeIndex" class="head" mode="horizontal">
        <el-menu-item index="1" @click="backHome">首页</el-menu-item>
        <el-menu-item index="2">用户管理</el-menu-item>
<!--          <el-submenu index="2-4">-->
<!--            <template slot="title">选项4</template>-->
<!--            <el-menu-item index="2-4-1">选项1</el-menu-item>-->
<!--            <el-menu-item index="2-4-2">选项2</el-menu-item>-->
<!--            <el-menu-item index="2-4-3">选项3</el-menu-item>-->
<!--          </el-submenu>-->
        <el-submenu index="3">
          <template slot="title">信息管理</template>
        <el-menu-item index="3-1">本校师生信息管理</el-menu-item>
        <el-menu-item index="3-2">外校信息管理</el-menu-item>
        </el-submenu>
        <el-menu-item index="4">见习信息管理</el-menu-item>
        <el-menu-item index="5">见习反馈</el-menu-item>
        <el-menu-item index="6">个人中心</el-menu-item>
        <el-menu-item index="7">退出登录</el-menu-item>
      </el-menu>
    </el-header>
    <el-main style="background-color: rgb(238, 241, 246)">
      <router-view></router-view><!--这是路由出口-->
    </el-main>
    <el-footer>
      <div class="copyright" style="float: bottom">
        <p>服务热线：XXXXXX Email：12345678@qq.com</p>
        <p>Copyright © 2020 internship.com 鄂ICP备14013441号-5</p>
      </div>
    </el-footer>
  </el-container>
</template>

<script>
    export default {
        name: "index",
        data(){
            return{
                activeIndex:'1'
            }
        },
        methods:{
            backHome(){
                this.$router.push({path:'/home/main'})
            }

        },
        created() {
            //接收从主界面传过来的参数
            console.log(this.$route.params.username)
            console.log(this.$route.params.password)
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
  .copyright{
    width:100%;
    height:60px;
    margin:0 auto;
    color:white;
    background-color: black;
    /*border-top: 1px solid #afaeae;*/
    position:relative;
  }
  .copyright p{
    padding-top: 10px;
    margin:0px auto;
    font-size: 12px;
    color: #bcb9b9;
    text-align: center;
  }

</style>
