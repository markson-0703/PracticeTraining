<template>
  <div class="homepage-hero-module">
  <div id="container">
    <div class="hold-space-div"></div>
  <body id="poster">
  <el-form class="login-container" label-position="left" label-width="0px">
    <h2 class="login_title">实习实训系统</h2>
    <el-form-item>
      <el-input type="text" v-model="loginForm.username" auto-complete="off" placeholder="账号"></el-input>
    </el-form-item>

    <el-form-item>
      <el-input type="password" v-model="loginForm.password" auto-complete="off" placeholder="密码"></el-input>
    </el-form-item>

    <el-form-item style="width: 100%">
      <el-button type="primary" style="width: 100%;background: dodgerblue;border: none" v-on:click="login">登录</el-button>
    </el-form-item>
    <p class="other"><router-link to="/register">没有账号？立即注册</router-link>|||<router-link to="/forget">忘记密码？立即找回</router-link></p>
    <!--<p class="other" ><a href="{{register}}">没有账号？立即注册</a>|<a href="{{forget}}">忘记密码？立即找回</a></p>-->
  </el-form>
  </body>
 <div class="hold-space-div"></div>
</div>
    <div class="video-container">
      <div :style="fixStyle" class="filter"></div>
      <video :style="fixStyle" autoplay loop class="fillWidth" v-on:canplay="canplay">
        <source src="static/cover/MP4/login.mp4" type="video/mp4"/>
        浏览器不支持 video 标签，建议升级浏览器。
        <source src="static/cover/MP4/login.webm" type="video/webm"/>
        浏览器不支持 video 标签，建议升级浏览器。
      </video>
      <div class="poster hidden" v-if="!vedioCanPlay">
        <img :style="fixStyle" src="static/cover/bg.jpg" alt="">
      </div>
    </div>
 </div>
</template>


<script>
  export default {
    name: "login",
    data() {
      return {
        loginForm: {
          username: '',
          password: ''
        },
          vedioCanPlay: false,
          fixStyle: '',
        responseResult: []
      }
    },
    methods: {

      login() {
        this.$http.post('/yii/home/index/login',{
          username:this.loginForm.username,
          password:this.loginForm.password,
        }).then(res=>{
          console.log(res.data)
          var message = res.data.message
          if(message=="登录成功")
          {
              alert(message)
            this.$store.dispatch('login',res.data.data)
            this.$store.dispatch('slogin',res.data.data)
            // alert(message)
            this.$router.push({name:'Main',params:{username:this.loginForm.username,password:this.loginForm.password}})
            console.log(this.$store.getters.getsToken)
          }
          else if(message=="该用户不存在")
          {
            alert("该用户不存在，请先注册")
          }
          else if(message=="密码错误，登录失败")
          {
            alert(message)
          }

          // console.log(localStorage.getItem("Token"))
        }
        ).catch(function (error) {
          console.log(error)
        })
      },
        canplay() {
            this.vedioCanPlay = true
        }
      // gotoReigster() {
      //   this.$router.push({
      //     path: "/register"
      //   });
      // },
      // gotoForget(){
      //   this.$router.push({
      //     path:"/forget"
      //   });
      // }
    },
    created:function ()
    {
      this.loginForm.username = this.$route.params.username;
      this.loginForm.password = this.$route.params.password
    },
      mounted() {
          window.onresize = () => {
              const windowWidth = document.body.clientWidth
              const windowHeight = document.body.clientHeight
              const windowAspectRatio = windowHeight / windowWidth
              let videoWidth
              let videoHeight
              if (windowAspectRatio < 0.5625) {
                  videoWidth = windowWidth
                  videoHeight = videoWidth * 0.5625
                  this.fixStyle = {
                      height: windowWidth * 0.5625 + 'px',
                      width: windowWidth + 'px',
                      'margin-bottom': (windowHeight - videoHeight) / 2 + 'px',
                      'margin-left': 'initial'
                  }
              } else {
                  videoHeight = windowHeight
                  videoWidth = videoHeight / 0.5625
                  this.fixStyle = {
                      height: windowHeight + 'px',
                      width: windowHeight / 0.5625 + 'px',
                      'margin-left': (windowWidth - videoWidth) / 2 + 'px',
                      'margin-bottom': 'initial'
                  }
              }
          }
      }
  }
</script>

<style>
  #poster {
    /*background:url("../assets/eva.jpg") no-repeat;*/
    background-position: center;
    height: 100%;
    width: 100%;
    background-size: cover;
    position: fixed;
    margin:0 auto;
  }
  body{
    margin: 0px;
    padding: 0;
  }
 #container{
  display: flex;
  flex-direction: column;
  justify-content: center;
  height: 100%;
  width: 100%;
  position: absolute;
  z-index: 100;
  color: #fff;
}
  .login-container {
    border-radius: 15px;
    background-clip: padding-box;
    margin: 150px auto;
    width: 350px;
    padding: 35px 35px 15px 35px;
    /*background: #fff;*/
    border: 2px solid #eaeaea;
    box-shadow: 0 0 25px #cac6c6;
  }

  .login_title {
    margin: 0px auto 40px auto;
    text-align: center;
    color: #505458;
  }
  .hold-space-div {
    flex: 1
  }

  .other {
    margin-top: 10px;
    font-size: 14px;
    line-height: 22px;
    color: #1ab2ff;
    cursor: pointer;
    text-align: center;
    /*text-indent: 8px;*/
    width: 360px;
  }
  .other:hover {
    /*color: #2c2fd6;*/
  }
  .video-container,.homepage-hero-module {
    position: relative;
    height: 100vh;
    overflow: hidden;
    margin:0px;
    padding: 0px;
  }

  .video-container .poster img,
  .video-container video {
    z-index: 0;
    position: relative;
    margin:0px;
    padding: 0px;
  }

  .video-container .filter {
    margin:0px;
    padding: 0px;
    z-index: 1;
    position: absolute;
    background: rgba(0, 0, 0, 0.3);
  }
  /*a{*/
  /*  text-decoration: none;*/
  /*  color:brown;*/
  /*  font-size: 14px;*/
  /*}*/
  router-link-active {
    text-decoration: none;
    color:#00d7c6
  }
</style>
