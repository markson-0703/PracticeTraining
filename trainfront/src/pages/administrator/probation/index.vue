<template>
  <el-container>
    <el-header>
      <el-menu :unique-opened="true" :default-active="$route.path" @select="handleSelect" class="head" mode="horizontal">
        <el-menu-item index="homepage" @click="backHome">首页</el-menu-item>
        <el-menu-item index="user">用户管理</el-menu-item>
        <el-menu-item index="member">信息管理</el-menu-item>
<!--        <el-submenu index="3">-->
<!--          <template slot="title">信息管理</template>-->
<!--        <el-menu-item index="3-1">本校师生信息管理</el-menu-item>-->
<!--        <el-menu-item index="3-2">外校信息管理</el-menu-item>-->
<!--        </el-submenu>-->
        <el-submenu index="4">
          <template slot="title">见习信息管理</template>
          <el-menu-item index="siteArrange">见习点分配</el-menu-item>
          <el-menu-item index="activityArrange">见习安排</el-menu-item>
        </el-submenu>
        <el-submenu index="5">
          <template slot="title">见习资源管理</template>
          <el-menu-item index="studentFile">见习实践记录</el-menu-item>
          <el-menu-item index="teacherFile">教师指导记录</el-menu-item>
        </el-submenu>
        <el-submenu index="6">
          <template slot="title">见习反馈</template>
          <el-submenu index="6-1">
            <template slot="title">学生的个人评价</template>
            <el-menu-item index="6-1-1">自我评价</el-menu-item>
            <el-menu-item index="6-1-2">小组评价</el-menu-item>
          </el-submenu>
          <el-menu-item index="6-2">教师总结与评价</el-menu-item>
          <el-menu-item index="6-3">见习成绩鉴定</el-menu-item>
        </el-submenu>
        <el-menu-item index="my">个人中心</el-menu-item>
        <el-menu-item index="8" @click="logout()">退出登录</el-menu-item>
      </el-menu>
    </el-header>
    <el-main style="background-color: white">
      <router-view v-if="showView"></router-view><!--这是路由出口-->
    </el-main>
<!--    <el-footer>-->
<!--      <div class="copyright" style="float: bottom">-->
<!--        <p>服务热线：XXXXXX Email：12345678@qq.com</p>-->
<!--        <p>Copyright © 2020 internship.com 鄂ICP备14013441号-5</p>-->
<!--      </div>-->
<!--    </el-footer>-->
  </el-container>
</template>

<script>
    export default {
        name: "index",
        data(){
            return{
                username:'',
                password:'',
                showView:true
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
            //接收从主界面传过来的参数
            this.username=this.$store.getters.getsName
            this.password=this.$store.getters.getsPwd
            console.log(this.$store.getters.getsName)
            console.log(this.$store.getters.getsPwd)
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
