<template>
  <div class="main-container">
    <div class="home-up">
      <img src="../../common/images/logo1.png">
      <h1 style="color:white; padding-top: 120px;padding-right:200px;font-size: 7.5vh;font-weight: 600;text-align: center">见习与实习的进程助手</h1>
      <div class="stream">
        <span>记录师范生的教学实践历程</span>
      </div>
      <div class="btnGroup">
        <div class="groupup">
      <div class="type1"><a class="jianxi" @click="goProbation">师范生见习</a></div>
      <div class="type2"><a class="shixi">师范生实习与研习</a></div>
        </div>
        <div class="groupdown">
      <div class="type3"><a class="weige">微格教学技能训练</a></div>
      <div class="type4"><a class="shijian">社会实践</a></div>
        </div>
      </div>
    </div>
      <div class="bg">
          <img src="../../common/images/bg2.jpg" alt="Avatar"/>
      </div>
    <div class="home-down">
      <h2 style="color:black; text-align: center;margin: 35px 0px 40px">实习实训系统的功能</h2>
      <div class="fun1">
        <img src="../../common/images/fun1.png">
        <h4>导师选择</h4>
        <span>
          在展开教学实践环节之前，学生选择导师来指导自己完成见习、实习与研习以及微格教学技能训练过程
        </span>

      </div>
      <div class="fun2">
        <img src="../../common/images/fun2.png">
        <h4>实践数据记录</h4>
        <span>
          系统将记录下教学实践过程中的数据，包括学生的听课、试讲、授课与反思记录，学生准备的教学素材（PPT、教学设计等），学生从事见习、实习和技能训练工作的记录，以及
          教师对学生教学和实践工作的指导
        </span>
      </div>
      <div class="fun3">
        <img src="../../common/images/fun3.png">
        <h4>总结与成绩鉴定</h4>
        <span>
          指导老师会对学生的教学实践工作作出最终的总结与评价，同时综合相应的考核量表对学生的见习、实习与研习以及微格技能训练表现给出成绩
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
                pwd:'',
                role:''
            }
        },
        methods:{
            goProbation(){
                if(this.role==1) {
                    this.$router.push({
                        path: '/administrator/probation/index',
                        name: 'ProbationIndex',
                        params: {username: this.name, password: this.pwd}
                    })
                }else if(this.role==2){
                    this.$router.push({
                        path: '/teacher/probation/index',
                        name: 'ProbationIndex2',
                        params: {username: this.name, password: this.pwd}
                    })

                }else if(this.role==3){
                    this.$router.push({
                        path: '/student/probation/index',
                        name: 'ProbationIndex1',
                        params: {username: this.name, password: this.pwd}
                    })
                }
            }
        },
        created:function () {
            this.name=this.$route.params.username
            this.pwd=this.$route.params.password
            this.role=this.$store.getters.getsRole
            console.log(this.$route.params.username)
            console.log(this.$route.params.password)
            console.log(this.$store.getters.getsName)
            console.log(this.$store.getters.getsRole)
        }
    }
</script>

<style scoped="scoped" type="text/css">
  .home-up{
    padding:0px;
    margin:0 auto;
  }
  .home-up img{
    float:left;
    width:150px;
    height:150px;
    padding:20px 50px;
  }
  .stream{
    margin: 100px 10px 80px 550px;
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
  .type1,.type2,.type3,.type4{
    width:400px;
    height:50px;
    background-color:#5090e2;
    border-radius: 10px;
    color:white;
    font-size: 24px;
    text-align: center;
    line-height:50px;
    cursor: pointer;
    transition: color .3s;
  }

  .btnGroup{
    margin:0px auto;
    padding:0px;
    height: 100%;
  }
  .groupup{
    height:50%;
    display: inline-block;
  }
  .groupdown{
     height:50%;
    display: inline-block;
   }
  .type1,.type3{
    float: left;
    margin:50px 180px;
  }
  .type2,.type4{
    float:right;
    margin:50px 180px;
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
