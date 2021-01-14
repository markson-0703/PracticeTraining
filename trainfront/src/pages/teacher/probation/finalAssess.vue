<template>
  <div class="display">
    <table id="finalStastics">
     <tr>
       <th>序号</th>
       <th>学号</th>
       <th>见习生姓名</th>
       <th>校外指导教师</th>
       <th>见习学校</th>
       <th>见习年级</th>
       <th>评定情况</th>
<!--       <th>自评情况</th>-->
<!--       <th>小组评价情况</th>-->
<!--       <th>校外导师评价情况</th>-->
<!--       <th>操作</th>-->
     </tr>
      <tr v-for="(item,index) in traineeInfo">
        <td>{{index+1}}</td>
        <td>{{item.sno}}</td>
        <td>{{item.sName}}</td>
        <td>{{item.tutorName}}</td>
        <td>{{item.school}}</td>
        <td>{{item.grade}}</td>
        <td>
         <span>
           <el-button type="primary" plain size="small" @click="teacherEvaluation(item.uname,item.tutorName)">查看</el-button>
         </span>
        </td>
      </tr>
    </table>
    <!--          评价基本情况显示-->
    <el-dialog title="见习评定的基本情况" :visible.sync="dialogVisible2" :before-close="handleClose" width="800px">
      <el-form :model="baseAssess">
        <el-form-item label="自我评价" :label-width="formLabelWidth">
          <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="baseAssess.selfContent" :disabled="true"></el-input>
        </el-form-item>
        <el-form-item label="小组评价" :label-width="formLabelWidth">
          <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="baseAssess.groupContent" :disabled="true"></el-input>
        </el-form-item>
        <el-form-item label="校外导师评价" :label-width="formLabelWidth">
          <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="baseAssess.tutContent" :disabled="true"></el-input>
        </el-form-item>
        <el-form-item label="校内导师评价" :label-width="formLabelWidth">
          <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="baseAssess.teaContent"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
           <el-button @click="dialogVisible2= false">取 消</el-button>
           <el-button type="primary" v-show="baseAssess.teaContent==''" @click="submitTeaassess">提交</el-button>
           <el-button type="primary" v-show="baseAssess.teaContent!=''" @click="updateTeaassess">更新</el-button>
           </span>
    </el-dialog>
<!--    校内导师评价-->
<!--    <el-dialog title="校内导师评价" :visible.sync="dialogVisible3" :before-close="handleClose" width="800px">-->
<!--      <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="teacherAssess">-->
<!--      </el-input>-->
<!--      <span slot="footer" class="dialog-footer">-->
<!--           <el-button @click="dialogVisible3= false">取 消</el-button>-->
<!--           <el-button @click=clear()>重置</el-button>-->
<!--           <el-button type="primary" @click=submitTeaassess()>提交</el-button>-->
<!--            </span>-->
<!--    </el-dialog>-->
  </div>
</template>

<script>
    export default {
        name: "finalAssess",
        data(){
            return{
                dialogVisible2:false,
                formLabelWidth:'120px',
                teacher:'',
                username:'',//教师的用户名
                stuUname:'',//当前学生的用户名
                traineeInfo:[],
                baseAssess:{
                    selfContent:'',//自我评价的内容
                    groupContent:'',//小组评价的内容
                    tutContent:'',//校外导师评价
                    teaContent:'',//校内导师评价
                }
            }
        },
        methods:{
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        done();
                    })
                    .catch(_ => {});
            },
            studentData(){
                //学生情况
                let that = this
                that.$http.post('/yii/evaluation/evaluation/studentdata',{
                    username:that.username
                }).then(function(res){
                    console.log(res.data)
                    that.traineeInfo=res.data.data
                })
            },
            teacherEvaluation(uname,tName){
                //校内教师评价
                let that = this
                that.stuUname=uname
                that.teacher=tName
                that.$http.post('/yii/evaluation/evaluation/totalsituation',{
                    uname:uname
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.dialogVisible2=true
                        that.baseAssess.selfContent=res.data.data.selfevaluation
                        that.baseAssess.groupContent=res.data.data.groupevaluation
                        that.baseAssess.tutContent=res.data.data.tutorevaluation
                        that.baseAssess.teaContent=res.data.data.teacherevaluation
                    }else{
                        this.$notify.error({
                            title: '错误',
                            message: '该学生还未完成自评！'
                        });
                    }
                })
            },
            submitTeaassess(){
                //提交校内导师的评价
                let that = this
                that.$http.post('/yii/evaluation/evaluation/teacherevaluation',{
                    username:that.stuUname,
                    content:that.baseAssess.teaContent,
                    teacher:that.teacher
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.dialogVisible2=false
                        this.$message({
                            message: '评价成功！',
                            type: 'success'
                        });
                    }else{
                        this.$message.error('评价失败！');
                    }
                }).catch(function(err){
                    console.log(err)
                })
            },
            updateTeaassess(){
                let that = this
                that.$http.post('/yii/evaluation/evaluation/teacherupdate',{
                    username:that.stuUname,//当前评价的学生
                    content:that.baseAssess.teaContent,
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.dialogVisible2=false
                        this.$message({
                            message: '校内评价已更新!',
                            type: 'success'
                        });
                    }
                })
            }
        },
        created() {
            this.username=this.$store.getters.getsName
            this.studentData()
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
