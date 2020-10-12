<template>
  <div class="main-container">
    <div class="home-up">
      <h1 style="color:white; padding-top: 150px;font-size: 7.5vh;font-family: PingFangSC-Semibold;font-weight: 600;">见习与实习的进程助手</h1>
      <div class="stream">
        <span>记录师范生的见习与实习历程</span>
      </div>
      <div class="btnGroup">
      <div class="type1"><a class="jianxi" @click="goProbation">师范生见习</a></div>
      <div class="type2"><a class="shixi">师范生实习</a></div>
      </div>
    </div>
      <div class="bg">
          <img src="../../common/images/bg2.jpg" alt="Avatar"/>
      </div>
    <div class="home-down">
      <h2 style="color:black; text-align: center;margin: 35px 0px 40px">实习实训系统的功能</h2>
      <div class="fun1">
        <img src="../../common/images/fun1.png">
        <h4>签到记录</h4>
        <span>
          记录下自己见习与实习的签到过程，监督自己准时签到打卡
        </span>

      </div>
      <div class="fun2">
        <img src="../../common/images/fun2.png">
        <h4>反思报告</h4>
        <span>
          在见习与实习报告上留下自己的心得感悟，记录自己见习或实习过程中的问题与收获，以进一步提高自己的专业能力
        </span>
      </div>
      <div class="fun3">
        <img src="../../common/images/fun3.png">
        <h4>评语反馈与成绩鉴定</h4>
        <span>
          指导老师会在见习与实习报告表上进行评注、留下评语，最终评定总成绩时综合考虑平时检查、教学现场考核、实习（见习）报告三部分的考核结果
        </span>
      </div>
    </div>
  </div>

</template>

<script>
    export default {
        name: "Main",
        data(){
            return{
                name:'',
                pwd:''
            }
        },
        methods:{
            goProbation(){
                this.$router.push({path:'/administrator/probation/index',name:'ProbationIndex',params:{username:this.name,password:this.pwd}})
            }
        },
        created:function () {
            this.name=this.$route.params.username
            this.pwd=this.$route.params.password
            console.log(this.$route.params.username)
            console.log(this.$route.params.password)
            console.log(this.$store.getters.getsName)
        }
    }
</script>

<style scoped="scoped" type="text/css">
  .home-up{
    padding:0px;
    margin:0 auto;
  }
  .stream{
    margin: 120px 10px 40px 500px;
    width:500px;
    height:60px;
    background-color: rgba(0, 0, 0, 0.5);
  }
  .stream span{
    font-size: 3.22vh;
    color: rgb(255, 255, 255);
    font-family: PingFangSC;
    font-weight: 400;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    text-align: center;
    padding:12px 80px;
  }
  .type1,.type2{
    width:400px;
    height:70px;
    background-color:#5090e2;
    border-radius: 10px;
    color:white;
    font-size: 24px;
    text-align: center;
    line-height:70px;
    cursor: pointer;
    transition: color .3s;
  }

  .btnGroup{
    margin:0px auto;
    padding:0px;
  }
  .type1{
    float: left;
    margin:100px 180px;
  }
  .type2{
    float:right;
    margin:100px 180px;
  }
  .bg{
    margin:0px auto;
  }
  .bg img{
    padding:0px;
    width:1600px;
    height:900px;
    position:relative;
    z-index: 1;
    background-size: auto 100%;
  }
  .home-up{
    z-index: 2;
    position: absolute;
  }
  .home-down{
    z-index: 2;
    position: absolute;
    margin:0 auto;
  }
  .home-down div{
    display:flex;
    width:330px;
    box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 4px 3px;
    flex-direction: column;
    background: rgb(255, 255, 255);
    border-radius: 4px;
    line-height: 25px;
  }
  .fun1{
    float:left;
    margin:20px 60px 20px 180px;
  }
  .fun2,.fun3{
    float: left;
    margin:20px 60px;
    display: inline-block;
  }
  .home-down img{
    margin-left: 100px;
    margin-top: 30px;
    width:130px;
  }
  .home-down span{
   padding:0px 10px 20px 10px;
  }
</style>
