<template>
  <div class="display" id="maintop">
    <el-tabs v-model="activeName" @tab-click="handleClick" style="height: fit-content;">
      <el-tab-pane label="自我评价" name="first">
        <div class="myAssess">
        <div class="assess">
          <el-form ref="form" :model="assessForm" label-width="120px">
            <el-form-item label="见习生姓名:" style="width:400px;" prop="sName">
              <el-input v-model="assessForm.sName"></el-input>
            </el-form-item>
            <el-form-item label="专业:" style="width:400px;" prop="major">
              <el-input v-model="assessForm.major"></el-input>
            </el-form-item>
            <el-form-item label="见习学校:" style="width:400px;" prop="school">
              <el-input v-model="assessForm.school"></el-input>
            </el-form-item>
            <el-form-item label="见习年级:" style="width:400px;" prop="grade">
              <el-input v-model="assessForm.grade"></el-input>
            </el-form-item>
            <el-form-item label="见习科目:" style="width:400px;" prop="subject">
              <el-input v-model="assessForm.subject"></el-input>
            </el-form-item>
            <el-form-item label="观摩节数:" style="width:400px;" prop="section">
            <el-input v-model="assessForm.section"></el-input>
           </el-form-item>
<!--            <el-form-item label="见习成绩:" style="width:400px;" prop="mark">-->
<!--              <el-input v-model="assessForm.mark"></el-input>-->
<!--            </el-form-item>-->
            <el-form-item label="见习开始时间:" style="width:400px;" prop="startTime">
              <el-input v-model="assessForm.startTime"></el-input>
            </el-form-item>
            <el-form-item label="见习结束时间:" style="width:400px;" prop="endTime">
              <el-input v-model="assessForm.endTime"></el-input>
            </el-form-item>
            <el-form-item label="自我评价（收获、优缺点）:" prop="selfevaluation">
              <quill-editor
                v-model="assessForm.selfevaluation"
                ref="myQuillEditor"
                :options="custom"
                @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
                @change="onEditorChange($event)"
              >
              </quill-editor>
            </el-form-item>
          </el-form>
        </div>
          <div class="actions">
            <el-button type="primary"  style="background-color:goldenrod;margin-right:50px" @click="submitInfo">提交</el-button>
            <el-button type="primary" @click="reset" style="background-color:darkcyan;margin-right:50px">重置</el-button>
            <el-button :plain="true" @click="open" v-show="haveAssess">见习评定提示</el-button>
          </div>
        </div>
      </el-tab-pane>
      <el-tab-pane label="师生评定" name="second">
        <h3>以下为见习小组、校内外导师对自身见习表现的评定！</h3>
        <div class="mainbottom">
          <el-form ref="form" :model="assessForm" label-width="140px" style="width:1000px;border:1px solid black;font-weight: bolder">
            <el-form-item label="见习小组评价:" prop="groupevaluation" style="width:400px;">
              <quill-editor
                v-model="assessForm.groupevaluation"
                ref="myQuillEditor"
                :options="newCustom"
                @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
                @change="onEditorChange($event)"
              >
              </quill-editor>
            </el-form-item>
            <el-form-item label="校外导师评价:" prop="tutorevaluation" style="width:400px;">
              <quill-editor
                v-model="assessForm.tutorevaluation"
                ref="myQuillEditor"
                :options="newCustom"
                @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
                @change="onEditorChange($event)"
              >
              </quill-editor>
            </el-form-item>
            <el-form-item label="院系指导教师评价:" prop="teacherevaluation" style="width:400px;">
              <quill-editor
                v-model="assessForm.teacherevaluation"
                ref="myQuillEditor"
                :options="newCustom"
                @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
                @change="onEditorChange($event)"
              >
              </quill-editor>
            </el-form-item>
          </el-form>
        </div>
      </el-tab-pane>
      <el-tab-pane label="组长评定" name="third" v-if="isLeader">
        <div class="onlyLeader">
          <table>
            <tr>
              <th>序号</th>
              <th>组员</th>
              <th>指导老师</th>
              <th>评定情况</th>
            </tr>
            <tr v-for="(item,index) in memberInfo">
             <td>{{index+1}}</td>
              <td>{{item.sName}}</td>
              <td>{{item.tName}}</td>
              <td>
                <span>
                 <el-button type="primary" plain size="small" @click="totalSituation(item.username,item.sName)">查看</el-button>
                </span>
              </td>
<!--              <td>-->
<!--                <span>-->
<!--                  <el-tag type="info" v-show="item.status==0||item.status==null">还未完成自评</el-tag>-->
<!--                  <el-button type="primary" plain v-show="item.status!=0&&item.status!=null" size="small" @click="seeEvaluation(item.username)">查看</el-button>-->
<!--                </span>-->
<!--              </td>-->
<!--              <td>-->
<!--                <span>-->
<!--                  <el-tag type="warning" v-show="item.status>1">已完成</el-tag>-->
<!--                   <el-button type="primary" plain v-show="item.status>1" size="small" @click="seeGroup(item.username)">查看</el-button>-->
<!--                  <el-button type="primary" size="small" v-show="item.status<2" @click="groupAssess(item.username)">评价</el-button>-->
<!--                </span>-->
<!--              </td>-->
            </tr>
          </table>
<!--          评价基本情况显示-->
          <el-dialog title="见习评定的基本情况" :visible.sync="dialogVisible" :before-close="handleClose" width="800px">
            <el-form :model="baseAssess">
              <el-form-item label="自我评价" :label-width="formLabelWidth">
            <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="baseAssess.selfContent" :disabled="true"></el-input>
              </el-form-item>
              <el-form-item label="小组评价" :label-width="formLabelWidth">
            <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="baseAssess.groupContent"></el-input>
              </el-form-item>
              <el-form-item label="校外导师评价" :label-width="formLabelWidth">
            <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="baseAssess.tutContent" :disabled="true"></el-input>
              </el-form-item>
              <el-form-item label="校内导师评价" :label-width="formLabelWidth">
            <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="baseAssess.teaContent" :disabled="true"></el-input>
              </el-form-item>
            </el-form>
           <span slot="footer" class="dialog-footer">
           <el-button @click="dialogVisible= false">取 消</el-button>
           <el-button type="primary" v-show="baseAssess.groupContent==''" @click="submitGroup">提交</el-button>
           <el-button type="primary" v-show="baseAssess.groupContent!=''" @click="updateGroup">更新</el-button>
           </span>
          </el-dialog>
        </div>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
    import { quillEditor } from 'vue-quill-editor'
    import * as Quill from 'quill'  //引入编辑器
    //quill编辑器的字体
    var fonts = ['SimSun', 'SimHei','Microsoft-YaHei','KaiTi','FangSong','Arial','Times-New-Roman','sans-serif'];
    var Font = Quill.import('formats/font');
    Font.whitelist = fonts; //将字体加入到白名单
    Quill.register(Font, true);
    import { addQuillTitle } from '../../../common/js/quill-title'
    export default {
        name: "assessResult",
        data(){
            return{
                activeName:"first",
                dialogVisible:false,
                formLabelWidth: '120px',
                isLeader:false,//判断是否为小组组长
                leaderName:'',//组长名字
                haveAssess:false,//判断评价状态是否不为1，发生了改变
                baseAssess:{
                    selfContent:'',//自我评价的内容
                    groupContent:'',//小组评价的内容
                    tutContent:'',//校外导师评价
                    teaContent:'',//校内导师评价
                },
                submitContent:'',
                currentUname:'',//当前评价对象
                assessForm:{
                    username:'',
                    sName:'',
                    tName:'',//校内
                    tutor_name:'',//校外
                    major:'',
                    school:'',
                    grade:'',
                    subject:'',
                    section:'',
                    mark:'',
                    startTime:'',
                    endTime:'',
                    selfevaluation:'',
                    groupevaluation:'',
                    tutorevaluation:'',
                    teacherevaluation:''
                },
                memberInfo:[],//组员信息
                custom:{
                    //自定义工具栏
                    theme:'bubble',
                    placeholder: "请在这里输入",
                    modules:{
                        toolbar:[
                            ['bold', 'italic', 'underline', 'strike'],        //加粗，斜体，下划线，删除线
                            // ['blockquote', 'code-block'],         //引用，代码块

                            [{ 'header': 1 }, { 'header': 2 }],               // 标题，键值对的形式；1、2表示字体大小
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],          //列表
                            [{ 'script': 'sub'}, { 'script': 'super' }],      // 上下标
                            [{ 'indent': '-1'}, { 'indent': '+1' }],          // 缩进
                            [{ 'direction': 'rtl' }],                         // 文本方向


                            [{ 'size': ['small', false, 'large', 'huge'] }],  // 字体大小
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],         //几级标题

                            [{ 'color': [] }, { 'background': [] }],          // 字体颜色，字体背景颜色
                            [{ 'font': [] }],         //字体
                            [{ 'align': [] }],        //对齐方式

                            ['clean'],        //清除字体样式
                            ['upload']
                            // ['link','image','video']        //上传图片、上传视频
                        ]
                    }
                },
                newCustom:{
                    //自定义工具栏
                    theme:'bubble',
                    placeholder: "暂无评价内容",
                    modules:{
                        toolbar:[
                            ['bold', 'italic', 'underline', 'strike'],        //加粗，斜体，下划线，删除线
                            [{ 'header': 1 }, { 'header': 2 }],               // 标题，键值对的形式；1、2表示字体大小
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],          //列表
                            [{ 'script': 'sub'}, { 'script': 'super' }],      // 上下标
                            [{ 'indent': '-1'}, { 'indent': '+1' }],          // 缩进
                            [{ 'direction': 'rtl' }],                         // 文本方向
                            [{ 'size': ['small', false, 'large', 'huge'] }],  // 字体大小
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],         //几级标题
                            [{ 'color': [] }, { 'background': [] }],          // 字体颜色，字体背景颜色
                            [{ 'font': [] }],         //字体
                            [{ 'align': [] }],        //对齐方式
                            ['clean'],        //清除字体样式
                            ['upload']
                        ]
                    }
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
            handleClick(tab, event) {
                console.log(tab, event);
            },
            open() {
                this.$message('可查看师生评定情况');
            },
            onEditorBlur(){//失去焦点事件
            },
            onEditorFocus(){//获得焦点事件
            },
            onEditorChange(){//内容改变事件
            },
            totalSituation(uname,sName){
                //评价基本情况
               console.log(uname)
               this.currentUname=uname
               this.leaderName=sName
               let that = this
                that.$http.post('/yii/evaluation/evaluation/totalsituation',{
                    uname:uname
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.baseAssess.selfContent=res.data.data.selfevaluation
                        that.baseAssess.groupContent=res.data.data.groupevaluation
                        that.baseAssess.tutContent=res.data.data.tutorevaluation
                        that.baseAssess.teaContent=res.data.data.teacherevaluation
                        that.dialogVisible=true
                    }else{
                        this.$notify.error({
                            title: '错误',
                            message: '该学生还未完成自评！'
                        });
                    }
                })
            },
            submitGroup(){
                //提交小组评价
               let that = this
                that.$http.post('/yii/evaluation/evaluation/groupevaluation',{
                    username:that.currentUname,
                    content:that.baseAssess.groupContent,
                    leader:that.leaderName
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.dialogVisible=false
                        this.$message({
                            message: '小组评价成功！',
                            type: 'success'
                        });
                    }else{
                        this.$message.error('小组评价失败！');
                    }
                })
            },
            updateGroup(){
              //已经小组评价过，对内容进行更新，状态不变
                let that = this
                that.$http.post('/yii/evaluation/evaluation/groupupdate',{
                    username:that.currentUname,//当前评价的学生
                    content:that.baseAssess.groupContent,
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.dialogVisible=false
                        this.$message({
                            message: '小组评价已更新!',
                            type: 'success'
                        });
                    }
                })
            },
            getInfo(){
               //获取基本情况
                let that = this
                that.$http.post('/yii/evaluation/evaluation/stubaseinfo',{
                   username:that.assessForm.username
                }).then(function(res){
                    console.log(res.data)
                    that.assessForm.sName=res.data.data[0].sName
                    that.assessForm.tName=res.data.data[0].tName
                    that.assessForm.tutor_name=res.data.data[0].tutor_name
                    that.assessForm.major=res.data.data[2]
                    that.assessForm.school=res.data.data[0].school_name
                    that.assessForm.grade=res.data.data[0].grade
                    that.assessForm.subject=res.data.data[1].subject
                    that.assessForm.section=res.data.data[1].section
                    that.assessForm.startTime=res.data.data[0].startTime
                    that.assessForm.endTime=res.data.data[0].endTime
                    that.assessForm.selfevaluation=res.data.data[1].selfevaluation
                    that.assessForm.groupevaluation=res.data.data[1].groupevaluation
                    that.assessForm.tutorevaluation=res.data.data[1].tutorevaluation
                    that.assessForm.teacherevaluation=res.data.data[1].teacherevaluation
                    that.getMember()
                    if(res.data.data[1].status>1){
                        that.haveAssess=true
                        }
                }).catch(function(err){
                    console.log(err)
                })
            },
            submitInfo(){
                //提交自我评价信息
                this.$confirm('确保填写完整后再上传?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    that.assessForm.selfevaluation= that.assessForm.selfevaluation.replace(/<[^>]+>/g, "")
                    that.$http.post('/yii/evaluation/evaluation/selfassess',{
                        username:that.assessForm.username,
                        sName:that.assessForm.sName,
                        major:that.assessForm.major,
                        school:that.assessForm.school,
                        grade:that.assessForm.grade,
                        subject:that.assessForm.subject,
                        section:that.assessForm.section,
                        startTime:that.assessForm.startTime,
                        endTime:that.assessForm.endTime,
                        selfevaluation:that.assessForm.selfevaluation
                    }).then(function(res){
                        console.log(res.data)
                        if(res.data.message=="success"){
                            alert('提交成功，自评完成')
                        }else{
                            alert(res.data.message)
                        }
                    })
                })
            },
            assessContent(){
                //获取其他的评价内容
                let that = this
                that.$http.post('/yii/evaluation/evaluation/assessinfo',{
                    username:that.assessForm.username
                }).then(function(res){
                    console.log(res.data)
                    that.assessForm.groupevaluation=res.data.data.groupevaluation
                    that.assessForm.tutorevaluation=res.data.data.tutorevaluation
                    that.assessForm.teacherevaluation=res.data.data.teacherevaluation
                }).catch(function(err){
                    console.log(err)
                })
            },
            reset(){
                //重置
                this.$refs.form.resetFields();
            },
            checkLeader(){
                //判断是否为小组组长
                let that = this
                that.$http.post('/yii/evaluation/evaluation/checkleader',{
                    username:that.assessForm.username
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.data.leader==1){
                        that.isLeader=true
                    }
                })
            },
            getMember(){
                //获取组员信息
                let that= this
                that.$http.post('/yii/evaluation/evaluation/mymember',{
                    school:that.assessForm.school,
                    tName:that.assessForm.tName
                }).then(function(res){
                    console.log(res.data)
                    that.memberInfo=res.data.data
                })
            }
        },
        created() {
            this.assessForm.username=this.$store.getters.getsName
            this.getInfo()
            this.assessContent()
            this.checkLeader()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import '../../../common/css/font.css';
  .display{
    text-align: center;
    height:100%;
  }
  .mainbottom{
    display: inline-block;
    font-weight: bolder;
  }
  .myAssess{
    display: inline-block;
    font-weight: bolder;
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
