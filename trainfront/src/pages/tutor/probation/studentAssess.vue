<template>
  <div class="display">
    <table id="assessStastics">
      <tr>
        <th>序号</th>
        <th>学号</th>
        <th>见习生姓名</th>
        <th>指导教师</th>
        <th>评定情况</th>
      </tr>
      <tr v-for="(item,index) in traineeData">
        <td>{{index+1}}</td>
        <td>{{item.sno}}</td>
        <td>{{item.sName}}</td>
        <td>{{item.tName}}</td>
        <td>
         <span>
           <el-button type="primary" plain size="small" @click="tutorEvaluation(item.uname,item.tutorName)">查看</el-button>
         </span>
        </td>
<!--        <td>-->
<!--          <span>-->
<!--            <el-tag type="info" v-show="item.status==0||item.status==null">还未完成自评</el-tag>-->
<!--            <el-button type="primary" plain v-show="item.status!=0&&item.status!=null" size="small" @click="checkSelf(item.uname,item.selfevaluation)">查看</el-button>-->
<!--          </span>-->
<!--        </td>-->
<!--        <td>-->
<!--           <span>-->
<!--            <el-tag type="info" v-show="item.status<2">还未完成小组评价</el-tag>-->
<!--            <el-button type="primary" plain v-show="item.status>1" size="small" @click="checkGroup(item.uname,item.groupevaluation)">查看</el-button>-->
<!--           </span>-->
<!--        </td>-->
<!--        <td>-->
<!--          <span>-->
<!--             <el-tag type="warning" v-show="item.status>2">已完成</el-tag>-->
<!--             <el-button type="primary" plain v-show="item.status>2" size="small" @click="seeTutor(item.uname)">查看</el-button>-->
<!--             <el-button type="primary" size="small" v-show="item.status<3" @click="tutorEvaluation(item.uname,item.tutorName)">评价</el-button>-->
<!--          </span>-->
<!--        </td>-->
      </tr>
    </table>

<!--    校外导师评价-->
<!--    <el-dialog title="校外导师评价" :visible.sync="dialogVisible2" :before-close="handleClose" width="800px">-->
<!--      <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="tutorAssess">-->
<!--      </el-input>-->
<!--      <span slot="footer" class="dialog-footer">-->
<!--           <el-button @click="dialogVisible2= false">取 消</el-button>-->
<!--           <el-button type="primary" @click=submitTutassess()>提交</el-button>-->
<!--            </span>-->
<!--    </el-dialog>-->
    <!--          评价基本情况显示-->
    <el-dialog title="见习评定的基本情况" :visible.sync="dialogVisible1" :before-close="handleClose" width="800px">
      <el-form :model="baseAssess">
        <el-form-item label="自我评价" :label-width="formLabelWidth">
          <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="baseAssess.selfContent" :disabled="true"></el-input>
        </el-form-item>
        <el-form-item label="小组评价" :label-width="formLabelWidth">
          <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="baseAssess.groupContent" :disabled="true"></el-input>
        </el-form-item>
        <el-form-item label="校外导师评价" :label-width="formLabelWidth">
          <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="baseAssess.tutContent"></el-input>
        </el-form-item>
        <el-form-item label="校内导师评价" :label-width="formLabelWidth">
          <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="baseAssess.teaContent" :disabled="true"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
           <el-button @click="dialogVisible1= false">取 消</el-button>
           <el-button type="primary" v-show="baseAssess.tutContent==''" @click="submitTutassess">提交</el-button>
           <el-button type="primary" v-show="baseAssess.tutContent!=''" @click="updateTutassess">更新</el-button>
           </span>
    </el-dialog>
  </div>
</template>

<script>
    export default {
        name: "studentAssess",
        data(){
            return{
                formLabelWidth: '120px',
                dialogVisible1:false,
                username:'',//教师的用户名
                traineeData:[],
                tutor:'',
                currentUname:'',//当前学生的用户名
                baseAssess:{
                    selfContent:'',//自我评价的内容
                    groupContent:'',//小组评价的内容
                    tutContent:'',//校外导师评价
                    teaContent:'',//校内导师评价
                },
                selfContent:'',//当前自我评价内容
                groupContent:'',//当前小组评价内容
                tutorAssess:'',//校外导师评价
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
            traineeInfo(){
                //获取见习生的基本情况
                let that = this
                that.$http.post('/yii/evaluation/evaluation/traineeinfo',{
                    username:that.username
                }).then(function(res){
                    console.log(res.data)
                    that.traineeData=res.data.data
                })
            },
            tutorEvaluation(uname,tname){
                //校外导师评价
                console.log(uname,tname)
                let that = this
                that.currentUname=uname
                that.tutor=tname
                that.$http.post('/yii/evaluation/evaluation/totalsituation',{
                    uname:uname
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.dialogVisible1=true
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

            submitTutassess(){
                //提交校外导师评价
                let that = this
                that.$http.post('/yii/evaluation/evaluation/tutorevaluation',{
                    username:that.currentUname,
                    content:that.baseAssess.tutContent,
                    tutor:that.tutor
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.dialogVisible1=false
                        this.$message({
                            message: '评价成功！',
                            type: 'success'
                        });
                    }else{
                        this.$message.error('评价失败！');
                    }
                })
            },
            updateTutassess(){
                let that = this
                that.$http.post('/yii/evaluation/evaluation/tutorupdate',{
                    username:that.currentUname,//当前评价的学生
                    content:that.baseAssess.tutContent,
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.dialogVisible1=false
                        this.$message({
                            message: '校外评价已更新!',
                            type: 'success'
                        });
                    }
                })
            }
        },
        created() {
            this.username=this.$store.getters.getsName
            this.traineeInfo()
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
