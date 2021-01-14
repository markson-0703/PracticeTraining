<template>
  <div class="display">
   <table>
     <table id="studentStastics">
       <tr>
         <th>序号</th>
         <th>姓名</th>
         <th>学号</th>
         <th>校内导师</th>
         <th>见习年级</th>
         <th>状态</th>
       </tr>
       <tr v-for="(item,index) in studentList">
         <td>{{index+1}}</td>
         <td>{{item.sName}}</td>
         <td>{{item.sno}}</td>
         <td>{{item.tName}}</td>
         <td>{{item.grade}}</td>
         <td v-if="item.status==1">有效</td>
         <td v-if="item.status==0">已失效</td>
       </tr>
     </table>
   </table>
    <el-tag  type="danger" effect="dark" style="float:left;margin-top: 30px">
      指导学生总数：{{this.total}}
    </el-tag>
  </div>
</template>

<script>
    export default {
        name: "studentData",
        data(){
            return{
                studentList:[],
                username:'',
                total:0//当前所带学生总数
            }
        },
        methods:{
            getstuList(){
                //获取学生基本情况
                let that = this
                that.$http.post('/yii/probation/infomation/getstulist',{
                    username:that.username
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.studentList=res.data.data
                    }
                    that.total=that.studentList.length
                })
            }
        },
        created() {
            this.username=this.$store.getters.getsName
            this.getstuList()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import "../../../common/css/pagination.css";
</style>
