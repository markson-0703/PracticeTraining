<template>
    <div id="header">
      <div class="logo" style="float: left">
        <router-link :to="{path:'/home/Index'}">
          <span class="mainTitle">实习实训平台</span>
        </router-link>
      </div>
      <div class="nav" style="float: left">
        <ul>
          <li>
            <router-link :to="{path:'/home/Index'}">
              <span>首页</span>
            </router-link>
          </li>
          <li class="xiaoyuan">
            <el-dropdown trigger="click">
             <span class="el-dropdown-link" style="cursor: pointer">
              校园<i class="el-icon-caret-bottom el-icon--right"></i>
             </span>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item class="clearfix">
                  见习通知
                  <el-badge class="mark" :value="0" />
                </el-dropdown-item>
                <el-dropdown-item class="clearfix">
                  实习通知
                  <el-badge class="mark" :value="0" />
                </el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </li>
        </ul>
      </div>
      <div class="user-nav" style="float: right">
        <ul>
          <li class="name">
            <span class="username">{{userName}}</span>
            <img src="../common/images/user.png" class="img-circle" alt="Avatar">
          </li>
          <li class="personal">
            <router-link :to="{ path:'/my'}">
              <i class="lnr lnr-user"></i> <span class="my">个人中心</span>
            </router-link>
          </li>
          <li v-on:click="logout" style="cursor: pointer">
            <i class="lnr lnr-exit"></i> <span class="out">退出登入</span>
          </li>
        </ul>
      </div>
      <div class="clearfix"></div>
    </div>
</template>

<script>
    export default {
        name: "Header",
      data() {
        return {
          activeIndex: '1',
          userName:''
        };
      },
      methods: {
        handleSelect(key, keyPath) {
          console.log(key, keyPath);
        },
          back(){
            //返回到上一级
              this.$router.go(-1)
          },
          logout(){
            this.$http.get('/yii/home/index/logout')
                .then((res)=>{
                    this.$store.dispatch('logout')
                    alert(res.data.message)
                    this.$router.push('/login')//重新定向到登录页面
                },(err) =>{
                    console.log(err)
                })
          }
      },
        created() {
            this.userName=this.$store.getters.getsName
        }
    }
</script>

<style scoped>
  #header{
    height:55px;
    width:100%;
    position: absolute;
    background-color: black;
    padding:0px;
    float: top;
  }
  li{
    list-style: none;
    float: left;
    padding:0px 20px;
    line-height: 30px;
  }
  a{
    text-decoration: none;
    color:white
  }
  router-link-active {
    text-decoration: none;
    color:#00d7c6
  }
  .username{
    color:white;
  }
  .my{
    color:white;
    font-size: 16px;
  }
  .out{
    color:white;
    font-size: 16px;
  }
  .logo{
    padding:8px 10px 8px 50px;
    margin:0 auto;
  }
  .mainTitle{
    color:#00d7c6;
    font-size: 28px;
    font-weight: bolder;
  }
  .xiaoyuan{
    color:white;
  }
  el-dropdown-item{
    color:sandybrown;
    background-color: blanchedalmond;
  }
</style>
