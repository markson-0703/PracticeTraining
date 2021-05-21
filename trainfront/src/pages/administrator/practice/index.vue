<template>
    <el-container>
      <el-header>
        <el-menu :unique-opened="true" :default-active="$route.path" @select="handleSelect" class="head" mode="horizontal">
          <el-menu-item index="1" @click="backHome">首页</el-menu-item>
          <el-menu-item index="user">用户管理</el-menu-item>
          <el-menu-item index="memberManage">信息管理</el-menu-item>
          <el-submenu index="4">
            <template slot="title">实习信息管理</template>
            <el-menu-item index="placeArrange">实习点分配</el-menu-item>
            <el-menu-item index="4-2">实习安排</el-menu-item>
          </el-submenu>
          <el-submenu index="5">
            <template slot="title">实习与研习资源管理</template>
            <el-menu-item index="5-1">实习生实践记录与资源</el-menu-item>
            <el-menu-item index="5-2">教师指导记录</el-menu-item>
          </el-submenu>
          <el-submenu index="6">
            <template slot="title">实习反馈</template>
            <el-menu-item index="evaluationResult">实习评定</el-menu-item>
            <el-menu-item index="gradeResult">实习成绩</el-menu-item>
          </el-submenu>
          <el-menu-item index="my">个人中心</el-menu-item>
          <el-menu-item index="8">消息管理</el-menu-item>
          <el-menu-item index="9" @click="logout()">退出登录</el-menu-item>
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
            backHome(){
                this.$router.push({path:'/home/main'})
            },
            handleSelect (path) {
                this.isShow=false
                this.$nextTick(()=>{
                    this.isShow=true
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
            console.log(this.$store.getters.getsName)
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
