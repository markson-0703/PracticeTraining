<template>
    <div class="display">
      <table id="markStastics">
        <tr>
          <th>序号</th>
          <th>学号</th>
          <th>见习生姓名</th>
          <th>校外指导教师</th>
          <th>见习学校</th>
          <th>见习年级</th>
          <th>评分情况</th>
          <th>操作</th>
        </tr>
        <tr v-for="(item,index) in markList">
          <td>{{index+1}}</td>
          <td>{{item.sno}}</td>
          <td>{{item.sName}}</td>
          <td>{{item.tutorName}}</td>
          <td>{{item.school}}</td>
          <td>{{item.grade}}</td>
          <td>
            <el-tag  effect="plain" size="small" v-if="item.mark!=null">已评分</el-tag>
            <el-tag type="danger"  effect="plain" size="small" v-if="item.mark==null">待评分</el-tag>
          </td>
          <td>
            <el-button type="success" size="small" v-if="item.mark!=null" @click="seeDetail(item.mark)">查看</el-button>
            <el-button type="danger" size="small" v-if="item.mark==null" @click="gotoMark(item.uname)">去评分</el-button>
          </td>
        </tr>
      </table>

    </div>
</template>


<script>
    export default {
        name: "setMark",
        data(){
            return{
                markList:[],
                seeMark:false,//分数的可见性
                username:''//校内教师用户名
            }
        },
        methods:{
           markData(){
               //获得基本情况
               let that= this
               that.$http.post('/yii/evaluation/mark/studentmark',{
                   username:that.username
               }).then(function(res){
                   console.log(res.data)
                   that.markList=res.data.data
               })
           },
            gotoMark(uname){
               //跳转到评分页面，同时传用户名
                this.$router.push({name:'GradeScaler',params:{username:uname}})
            },
            seeDetail(mark){
                this.$message({
                    showClose: true,
                    message: '当前学生的见习成绩为:'+mark
                });
            }
        },
        created() {
            this.username=this.$store.getters.getsName
            this.markData()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  .display{
    text-align: center;
    height:100%;
  }
  table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 10px;
    table-layout:fixed;
  }
  th {
    font-size: 14px;
    border: solid 1px #808080;
    font-weight: bold;
    padding: 5px;
    background-color: #edebe2;
    text-align: center;
    position:-webkit-sticky;
    position:sticky;
    width:30px;
  }
  td{
    border: solid 1px #ccc;
    padding: 5px;
    text-align: center;
    font-size: 14px;
    word-wrap: break-word;
  }
</style>
