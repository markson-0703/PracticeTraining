<template>
  <el-tabs v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="实习教学设计指导" name="first">
      <table id="theirDesigns">
        <tr>
          <th>序号</th>
          <th>学号</th>
          <th>学生姓名</th>
          <th>教学设计数据</th>
          <th>审阅状态</th>
        </tr>
        <tr v-for="(item,index) in designData">
         <td>{{index+1}}</td>
         <td>{{item.sno}}</td>
         <td>{{item.sName}}</td>
         <td>
           {{item.design}}条数据
           <span><el-button size="small" type="primary" plain @click="detail(item.username)" v-show="item.status!=2">详情</el-button></span>
         </td>
          <td>
            <span>
            <el-tag type="success" v-show="item.status==1">已审阅</el-tag>
            <el-tag type="info" v-show="item.status==0">待审阅</el-tag>
            <el-tag type="warning" v-show="item.status==2">暂未有提交</el-tag>
            </span>
          </td>
        </tr>
      </table>
<!--      显示教学设计详情-->
      <el-dialog title="教学设计详情" :visible.sync="dialogVisible" :before-close="handleClose" width="800px">
        <table>
          <tr>
            <th>id号</th>
            <th>提交时间</th>
            <th>详情</th>
            <th>操作</th>
          </tr>
          <tr v-for="item in detailData">
            <td>{{item.rId}}</td>
            <td>{{item.submitTime}}</td>
            <td>
              <span>
                <el-button style="cursor: pointer" type="primary" icon="el-icon-view" circle @click="seeDesign(item.rId)"></el-button>
              </span>
            </td>
            <td>
              <span v-show="item.suggestion!=null"><el-button style="cursor: pointer" type="info" plain @click="reviewContent(item.rId)">查看审阅</el-button></span>
              <span v-show="item.suggestion==null"><el-button style="cursor: pointer" type="info" plain @click="submitContent(item.rId)">提交审阅</el-button></span>
            </td>
          </tr>
        </table>
      </el-dialog>
<!--      查看审阅详情-->
      <el-dialog title="审阅意见详情" :visible.sync="dialogVisible1" :before-close="handleClose" width="800px">
        <el-form :model="reviewForm">
        <el-form-item label="审阅意见" :label-width="formLabelWidth">
          <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="reviewForm.content" :disabled="true"></el-input>
        </el-form-item>
        </el-form>
      </el-dialog>
<!--      提交审阅意见-->
      <el-dialog title="教学设计审阅" :visible.sync="dialogVisible2" :before-close="handleClose" width="800px">
        <el-form :model="reviewForm">
        <el-form-item label="审阅意见" :label-width="formLabelWidth">
          <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="reviewForm.content"></el-input>
        </el-form-item>
        </el-form>
        <span slot="footer" class="dialog-footer">
           <el-button @click="dialogVisible2= false">取 消</el-button>
           <el-button type="primary" @click="submit">提交</el-button>
        </span>
      </el-dialog>
    </el-tab-pane>
    <el-tab-pane label="实习授课指导" name="second">
      <table id="instructStastics">
        <tr>
          <th>序号</th>
          <th>学号</th>
          <th>学生姓名</th>
          <th>指导老师</th>
          <th>详情</th>
        </tr>
        <tr v-for="(item,index) in stuList">
          <td>{{index+1}}</td>
          <td>{{item.sno}}</td>
          <td>{{item.sName}}</td>
          <td>{{item.tName}}</td>
          <td>
            <span>
              <el-button type="primary" plain size="small" @click="seeStudent(item.username)">查看</el-button>
            </span>
          </td>
        </tr>
      </table>
<!--      查看每个学生的实习授课-->
      <el-dialog title="校外导师评价" :visible.sync="elDialog" :before-close="handleClose" width="800px">
        <table id="perStastics">
          <tr>
            <th>序号</th>
            <th>日期</th>
            <th>星期</th>
            <th>授课班级</th>
            <th>课型</th>
            <th>授课内容</th>
            <th>评价详情</th>
          </tr>
          <tr v-for="(item,index) in assessInfo">
            <td>{{index+1}}</td>
            <td>{{item.time}}</td>
            <td>{{item.weekday}}</td>
            <td>{{item.class}}</td>
            <td>{{item.classform}}</td>
            <td>{{item.content}}</td>
            <td v-if="item.status>1">
              <span style="cursor: pointer"><el-button type="danger" size="small" plain @click="finalAssess(item.id)">评价表详情</el-button></span>
            </td>
            <td v-if="item.status==1">
              <span style="cursor: pointer"><el-button :plain="true" size="small" @click="errorTip">评价表详情</el-button></span>
            </td>
          </tr>
        </table>
      </el-dialog>
<!--      每一份评价表的具体内容-->
      <el-dialog title="授课评价表" :visible.sync="elDialog1" :before-close="handleClose" width="800px">
        <el-form :model="assessForm">
          <el-form-item label="自我评价" :label-width="formLabelWidth">
            <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="assessForm.selfContent" :disabled="true"></el-input>
          </el-form-item>
          <el-form-item label="小组评价" :label-width="formLabelWidth">
            <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="assessForm.groupContent" :disabled="true"></el-input>
          </el-form-item>
          <el-form-item label="指导老师意见" :label-width="formLabelWidth">
            <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="assessForm.tutContent" placeholder="请输入指导意见"></el-input>
          </el-form-item>
        </el-form>
        <span slot="footer" class="dialog-footer">
           <el-button @click="elDialog1= false">取 消</el-button>
           <el-button type="primary" v-show="this.flag" @click="submitTut(assessForm.id)">提交</el-button>
           <el-button type="primary" v-show="!this.flag" @click="updateTut(assessForm.id)">更新</el-button>
      </span>
      </el-dialog>
    </el-tab-pane>
    <el-tab-pane label="班级工作计划指导" name="third">
      <table id="theirPlan">
        <tr>
          <th>序号</th>
          <th>学号</th>
          <th>学生姓名</th>
          <th>班级工作计划数据</th>
          <th>审阅状态</th>
        </tr>
        <tr v-for="(item,index) in planData">
          <td>{{index+1}}</td>
          <td>{{item.sno}}</td>
          <td>{{item.sName}}</td>
          <td>
            {{item.design}}条数据
            <span><el-button size="small" type="primary" plain @click="formore(item.username)" v-show="item.status!=2">详情</el-button></span>
          </td>
          <td>
            <span>
            <el-tag type="success" v-show="item.status==1">已审阅</el-tag>
            <el-tag type="info" v-show="item.status==0">待审阅</el-tag>
            <el-tag type="warning" v-show="item.status==2">暂未有提交</el-tag>
            </span>
          </td>
        </tr>
      </table>
<!--      显示班级工作详情-->
      <el-dialog title="班级工作计划详情" :visible.sync="dialog" :before-close="handleClose" width="800px">
        <table>
          <tr>
            <th>id号</th>
            <th>提交时间</th>
            <th>详情</th>
            <th>操作</th>
          </tr>
          <tr v-for="item in workData">
            <td>{{item.rId}}</td>
            <td>{{item.submitTime}}</td>
            <td>
              <span>
                <el-button style="cursor: pointer" type="primary" icon="el-icon-view" circle @click="seeDesign(item.rId)"></el-button>
              </span>
            </td>
            <td>
              <span v-show="item.suggestion!=null"><el-button style="cursor: pointer" type="info" plain @click="reviewPlan(item.rId)">查看审阅</el-button></span>
              <span v-show="item.suggestion==null"><el-button style="cursor: pointer" type="info" plain @click="submitPlan(item.rId)">提交审阅</el-button></span>
            </td>
          </tr>
        </table>
      </el-dialog>
      <!--      查看审阅详情-->
      <el-dialog title="审阅意见详情" :visible.sync="dialogVisible3" :before-close="handleClose" width="800px">
        <el-form :model="directForm">
          <el-form-item label="审阅意见" :label-width="formLabelWidth">
            <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="directForm.advice" :disabled="true"></el-input>
          </el-form-item>
        </el-form>
      </el-dialog>
      <!--      提交审阅意见-->
      <el-dialog title="班级工作计划审阅" :visible.sync="dialogVisible4" :before-close="handleClose" width="800px">
        <el-form :model="directForm">
          <el-form-item label="审阅意见" :label-width="formLabelWidth">
            <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="directForm.advice"></el-input>
          </el-form-item>
        </el-form>
        <span slot="footer" class="dialog-footer">
           <el-button @click="dialogVisible4= false">取 消</el-button>
           <el-button type="primary" @click="handIn">提交</el-button>
        </span>
      </el-dialog>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
    export default {
        name: "Allguidance",
        data(){
            return{
                activeName:'first',
                designData:[],
                detailData:[],
                planData:[],
                workData:[],
                stuList:[],
                flag:false,
                assessInfo:[],//评价信息，此时小组评价已完成
                username:'',
                reviewForm:{
                    content:''
                },
                directForm:{
                    advice:''
                },
                formLabelWidth: '120px',
                dialog:false,
                dialogVisible:false,
                dialogVisible1:false,
                dialogVisible2:false,
                dialogVisible3:false,//查看
                dialogVisible4:false,//提交
                elDialog:false,
                elDialog1:false,
                assessForm:{
                    id:'',
                    selfContent:'',
                    groupContent:'',
                    tutContent:''
                },
                currentId:'',//当前提交审阅的ID号
                nowId:''
            }
        },
        methods:{
            handleClick(tab, event) {
                console.log(tab, event);
            },
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        done();
                    })
                    .catch(_ => {});
            },
            errorTip(){
                this.$message.error('该名学生还未完成小组评价！');
            },
            stuInfo(){
                let that = this
                that.$http.post('/yii/evaluation/assess/allstudent',{
                    username:that.username
                }).then(function(res){
                    console.log(res.data)
                    that.stuList=res.data.data
                })
            },
            finalAssess(id){
                let that = this
                that.$http.post('/yii/evaluation/assess/everyform',{
                    id:id
                }).then(function(res){
                    if(res.data.message=="success"){
                        this.assessForm.id=res.data.data.id
                        that.assessForm.selfContent=res.data.data.selfAssess
                        that.assessForm.groupContent=res.data.data.groupAssess
                        that.assessForm.tutContent=res.data.data.tutorAssess
                        that.elDialog1=true
                        if(res.data.data.tutorAssess==null){
                            this.flag=true
                        }else{
                            this.flag=false
                        }
                    }
                })
            },
            submitTut(currentId){
                let that = this
                that.$http.post('/yii/evaluation/assess/tutsubmit',{
                    id:currentId,
                    content:that.assessForm.tutContent,
                }).then(function(res){
                    if(res.data.message=="success"){
                        that.elDialog1=false
                        this.$message({
                            message: '审阅成功！',
                            type: 'success'
                        });
                    }else{
                        this.$message.error('审阅失败！');
                    }
                })
            },
            updateTut(currentId){
                let that = this
                that.$http.post('/yii/evaluation/assess/tutupdate',{
                    id:currentId,
                    content:that.assessForm.tutContent,
                }).then(function(res){
                    if(res.data.message=="success"){
                        that.elDialog1=false
                        this.$message({
                            message: '审阅更新成功！',
                            type: 'success'
                        });
                    }
                })
            },
            seeStudent(uname){
                let that = this
                that.$http.post('/yii/evaluation/assess/everystudent',{
                    username:uname
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.assessInfo=res.data.data
                        that.elDialog=true
                    }
                })
            },
            baseInfo(){
                let that= this
                that.$http.post('/yii/resource/check/baseinfo',{
                    username:this.username,
                    kind:2
                }).then(function(res){
                    console.log(res.data.data)
                    if(res.data.message=="success"){
                        that.designData=res.data.data
                    }
                })
            },
            memberInfo(){
                let that = this
                that.$http.post('/yii/resource/check/baseinfo',{
                    username:this.username,
                    kind:3
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.planData=res.data.data
                    }
                })
            },
            detail(uname){
                //获取详情
                let that = this
                that.$http.post('/yii/resource/check/designinfo',{
                    username:uname,
                    kind:2
                }).then(function(res){
                    console.log(res.data)
                    that.dialogVisible=true
                    that.detailData=res.data.data
                })
            },
            formore(uname){
                //获取工作计划详情
                let that = this
                that.$http.post('/yii/resource/check/designinfo',{
                    username:uname,
                    kind:3
                }).then(function(res){
                    console.log(res.data)
                    that.dialog=true
                    that.workData=res.data.data
                })
            },
            reviewContent(id){
                //查看审阅内容，以一个提示框即可
                let that= this
                that.$http.post('/yii/resource/check/reviewcontent',{
                    id:id
                }).then(function(res){
                    console.log(res.data)
                    that.reviewForm.content=res.data.data
                    this.dialogVisible1=true
                })
            },
            reviewPlan(id){
                let that= this
                that.$http.post('/yii/resource/check/reviewcontent',{
                    id:id
                }).then(function(res){
                    console.log(res.data)
                    that.directForm.advice=res.data.data
                    this.dialogVisible3=true
                })
            },
            submitContent(id){
              this.reviewForm.content =''
              this.dialogVisible2=true
              this.currentId=id
            },
            submitPlan(id){
                this.directForm.advice =''
                this.dialogVisible4=true
                this.nowId=id
            },
            submit(){
               let that = this
                that.$http.post('/yii/resource/check/submitcontent',{
                    id:that.currentId,
                    content:that.reviewForm.content
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        alert('审阅提交成功！')
                        this.memberInfo()
                        this.dialogVisible4=false
                    }
                })
            },
            handIn(){
                let that = this
                that.$http.post('/yii/resource/check/submitcontent',{
                    id:that.nowId,
                    content:that.directForm.advice
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        alert('审阅提交成功！')
                        this.baseInfo()
                        this.dialogVisible2=false
                    }
                })
            },
            seeDesign(id){
              //查看当前教学设计的内容
                let that = this
                that.$http.post('/yii/resource/check/getlink',{
                    id:id
                }).then(function(res){
                    console.log(res.data.data)
                    var originUrl='http://127.0.0.1/'+res.data.data
                    window.open("http://127.0.0.1:8012/onlinePreview?url="+encodeURIComponent(originUrl),"_blank")
                })
            },
        },
        created() {
            this.username=this.$store.getters.getsName
            this.baseInfo()
            this.stuInfo()
            this.memberInfo()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import '../../../common/css/font.css';
  @import '../../../common/css/templateTable.css';
</style>
