<template>
  <div class="personal">
    <!--      该页面属于管理员实习部分的个人中心-->
    <el-tabs v-model="activeName" @tab-click="handleClick">
      <el-tab-pane label="个人信息" name="first">
        <h1 style="font-weight:bold;font-color:black">{{this.content}}</h1>
      </el-tab-pane>
      <el-tab-pane label="修改密码" name="second">
        <el-form :model="myForm" status-icon :rules="rules" ref="myForm" label-width="100px" class="ruleForm">
          <el-form-item label="旧密码" style="width:400px" prop="old">
            <el-input type="password" v-model="myForm.old" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="新密码" style="width:400px" prop="pass">
            <el-input type="password" v-model="myForm.pass" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="确认密码" style="width:400px" prop="checkPass">
            <el-input type="password" v-model="myForm.checkPass" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitForm('myForm')">提交</el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
      <el-tab-pane label="模板上传" name="third">
        <h1 style="font-weight:bold;font-color:black">
          请在此处上传教育实习与研习过程中的教学设计模板
        </h1>
        <el-tabs :tab-position="tabPosition">
          <el-tab-pane label="学生模板">
            <el-upload class="template-upload" ref="temForm" action="/yii/template/template/uploadstu"
                       :on-preview="handlePreview" :on-remove="handleRemove" :file-list="fileList" :auto-upload="false"
                       :on-change="handleChanged" :before-remove="beforeRemove" :on-success="uploadSuccess" :on-error="uploadError"
                       :data="uploadData" :before-upload="beforeUpload">
              <el-dropdown @command="handleCommand">
            <span class="el-dropdown-link">
            模板类型<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item command="1">实习听课记录</el-dropdown-item>
                  <el-dropdown-item command="2">教学实习教学设计</el-dropdown-item>
                  <el-dropdown-item command="3">班级工作计划</el-dropdown-item>
                  <el-dropdown-item command="4">班主任工作特殊事项记载</el-dropdown-item>
                  <el-dropdown-item command="5">主题班会记录表</el-dropdown-item>
                  <el-dropdown-item command="6">班级活动记录表</el-dropdown-item>
                  <el-dropdown-item command="7">个别教育记录表</el-dropdown-item>
                  <el-dropdown-item command="8">家长会记录</el-dropdown-item>
                  <el-dropdown-item command="9">教育研究报告</el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
              <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
              <el-button style="margin-left: 10px;" size="small" type="success" @click="upload">上传</el-button>
              <div slot="tip" class="el-upload__tip">仅支持word文档的上传</div>
            </el-upload>
            <table id="templateStastics">
              <tr>
                <th style="width:3%">Id</th>
                <th style="width:7%">模板类型</th>
                <th style="width:7%">文件名称</th>
                <th style="width:5%">发布时间</th>
                <th style="width:3%">操作</th>
              </tr>
              <tr v-for="(item,index) in templateData">
                <td>{{index+1}}</td>
                <td v-if="item.kind==1">实习听课记录</td>
                <td v-if="item.kind==2">教学实习教学设计</td>
                <td v-if="item.kind==3">班级工作计划</td>
                <td v-if="item.kind==4">班主任工作特殊事项记载</td>
                <td v-if="item.kind==5">主题班会记录表</td>
                <td v-if="item.kind==6">班级活动记录表</td>
                <td v-if="item.kind==7">个别教育记录表</td>
                <td v-if="item.kind==8">家长会记录</td>
                <td v-if="item.kind==9">教育研究报告</td>
                <td>{{item.filename}}</td>
                <td>{{item.publishTime}}</td>
                <td>
                  <span style="cursor: pointer" @click="del(item.temId)"><el-button type="danger" icon="el-icon-delete" circle></el-button></span>
                </td>
              </tr>
            </table>
          </el-tab-pane>
          <el-tab-pane label="校内教师模板">
            <el-upload class="template-upload" ref="temForm1" action="/yii/template/template/uploadtea"
                       :on-preview="handlePreview1" :on-remove="handleRemove1" :file-list="temList" :auto-upload="false"
                       :on-change="handleChanged1" :before-remove="beforeRemove1" :on-success="uploadSuccess1" :on-error="uploadError1"
                       :data="myData" :before-upload="beforeUpload1">
              <el-dropdown @command="handleCommand1">
            <span class="el-dropdown-link">
            模板类型<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item command="1">实习动员大会会议记录</el-dropdown-item>
                  <el-dropdown-item command="2">实习工作计划</el-dropdown-item>
                  <el-dropdown-item command="3">实习生试讲实录</el-dropdown-item>
                  <el-dropdown-item command="4">实习生授课实录</el-dropdown-item>
                  <el-dropdown-item command="5">实习生公开课试讲实录</el-dropdown-item>
                  <el-dropdown-item command="6">实习生公开课实录</el-dropdown-item>
                  <el-dropdown-item command="7">班主任工作指导记录表</el-dropdown-item>
                  <el-dropdown-item command="8">教育研究指导记录表</el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
              <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
              <el-button style="margin-left: 10px;" size="small" type="success" @click="upload1">上传</el-button>
              <div slot="tip" class="el-upload__tip">仅支持word文档的上传</div>
            </el-upload>
            <table id="templateStastics1">
              <tr>
                <th style="width:3%">Id</th>
                <th style="width:7%">模板类型</th>
                <th style="width:7%">文件名称</th>
                <th style="width:5%">发布时间</th>
                <th style="width:3%">操作</th>
              </tr>
              <tr v-for="(item,index) in teacherTem">
                <td>{{index+1}}</td>
                <td v-if="item.kind==1">实习动员大会会议记录</td>
                <td v-if="item.kind==2">实习工作计划</td>
                <td v-if="item.kind==3">实习生试讲实录</td>
                <td v-if="item.kind==4">实习生授课实录</td>
                <td v-if="item.kind==5">实习生公开课试讲实录</td>
                <td v-if="item.kind==6">实习生公开课实录</td>
                <td v-if="item.kind==7">班主任工作指导记录表</td>
                <td v-if="item.kind==8">教育研究指导记录表</td>
                <td>{{item.filename}}</td>
                <td>{{item.publishTime}}</td>
                <td>
                  <span style="cursor: pointer" @click="del(item.temId)"><el-button type="danger" icon="el-icon-delete" circle></el-button></span>
                </td>
              </tr>
            </table>
          </el-tab-pane>
          <el-tab-pane label="校外教师模板">
          </el-tab-pane>
        </el-tabs>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
    export default {
        name: "my",
        data(){
            var validatePass = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请输入密码'))
                } else {
                    if (this.myForm.checkPass !== '') {
                        this.$refs.myForm.validateField('checkPass')
                    }
                    callback()
                }
            }
            var validatePass2 = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请再次输入密码'))
                } else if (value !== this.myForm.pass) {
                    callback(new Error('两次输入密码不一致!'))
                } else {
                    callback()
                }
            }
            return{
                activeName:'first',
                content:'欢迎您，管理员',
                tabPosition:'left',
                kind:0,//实习部分学生模板的类型
                form:0,//实习部分教师模板的类型
                myData:{
                },
                uploadData:{
                },
                templateData:[],
                teacherTem:[],//教师模板数据
                fileList:[],
                temList:[],
                myForm:{
                    username:'',
                    pass: '',
                    checkPass: '',
                    old: ''
                },
                rules: {
                    pass: [
                        { validator: validatePass, trigger: 'blur' }
                    ],
                    checkPass: [
                        { validator: validatePass2, trigger: 'blur' }
                    ],
                    old: [
                        { validator: validatePass, trigger: 'blur' }
                    ]
                }
            }
        },
        methods:{
            upload(){
                if(this.kind==0){
                    this.$message.error('请务必选择模板类型之后再上传!');
                }else{
                    this.$refs.temForm.submit()
                }
            },
            upload1(){
                //教师模板上传
                if(this.form==0){
                    this.$message.error('请务必选择模板类型之后再上传!');
                }else{
                    this.$refs.temForm1.submit()
                }
            },
            submitForm(formName){
                this.$refs[formName].validate((valid)=>{
                    if(valid){
                        this.load()
                    }else{
                        console.log('error submit!')
                        return false
                    }
                })
            },
            load(){
                this.$http.post('/yii/home/index/alteraccount',data)
                    .then((res)=>{
                        console.log(res.data)
                        var msg=res.data.message
                        if(msg="密码修改成功"){
                            alert(msg)
                            this.$store.dispatch('logout')
                            this.$router.push('/login')
                        }else if(msg="密码修改失败"){
                            alert(msg)
                        }else if(msg="旧密码错误"){
                            alert("旧密码输入有误")
                        }else if(msg="用户不存在"){
                            alert("操作有误")
                        }
                    })
            },
            handleClick(tab,event){
                console.log(tab,event)
            },
            handlePreview1(file){
                console.log(file);//打印出了文件信息
            },
            handlePreview(file){
                console.log(file);//打印出了文件信息
            },
            handleRemove(file){
                console.log(file);
            },
            handleRemove1(file){
                console.log(file);
            },
            handleChanged(file){
                console.log(file)
            },
            handleChanged1(file){
                console.log(file)
            },
            beforeRemove(file) {
                return this.$confirm(`确定移除 ${ file.name }？`);
            },
            beforeRemove1(file) {
                return this.$confirm(`确定移除 ${ file.name }？`);
            },
            uploadSuccess(res){
                if(res.code==200){
                    this.$message({
                        message: '恭喜你，模板上传成功',
                        type: 'success'
                    });
                    this.getstuTem()
                    console.log(res.data)
                }else{
                    this.$message({
                        message: res.message,
                        type: 'warning'
                    });
                }
            },
            getstuTem(){
                let that = this
                that.$http.post('/yii/template/template/stutemplate',{
                    username:that.myForm.username
                }).then(function(res){
                    console.log(res.data)
                    that.templateData=res.data.data
                })
            },
            uploadSuccess1(res){
                if(res.code==200){
                    this.$message({
                        message: '模板上传成功!',
                        type: 'success'
                    });
                    this.getteacherTem()
                    console.log(res.data)
                }else{
                    this.$message({
                        message: res.message,
                        type: 'warning'
                    });
                }
            },
            getteacherTem(){
                let that = this
                that.$http.post('/yii//template/template/teatemplate1',{
                    username:that.myForm.username
                }).then(function(res){
                    console.log(res.data)
                    that.teacherTem=res.data.data
                })
            },
            uploadError1(){
                this.$refs.temForm1.clearFiles();
                this.$message.error('上传失败，请重新上传!');
            },
            uploadError(){
                this.$refs.temForm.clearFiles();
                this.$message.error('上传失败，请重新上传!');
            },
            handleCommand(command) {
                //学生
                console.log(command)
                this.kind=parseInt(command)
            },
            handleCommand1(command) {
                //校内教师
                console.log(command)
                this.form=parseInt(command)
            },
            beforeUpload(file){
                this.uploadData = {
                    username:this.$store.getters.getsName,
                    kind:this.kind
                }
                console.log(this.uploadData)
                let promise = new Promise((resolve) => {
                    this.$nextTick(function () {
                        resolve(true);
                    });
                });
                return promise;
            },
            beforeUpload1(file){
                this.myData = {
                    username:this.$store.getters.getsName,
                    kind:this.form
                }
                let promise = new Promise((resolve) => {
                    this.$nextTick(function () {
                        resolve(true);
                    });
                });
                return promise;
            },
            del(id){
                //删除模板
                this.$confirm('此操作将永久删除该模板, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    that.$http.post('/yii/template/template/deltemplate',{
                        id:id
                    }).then(function(res){
                        console.log(res.data)
                        if(res.data.message=="success"){
                            that.getstuTem()
                            that.getteacherTem()
                            // that.gettutorTem()
                            alert('删除成功!')
                        }else{
                            alert('操作失败!')
                        }
                    })
                })
            }
        },
        created() {
            this.myForm.username=this.$store.getters.getsName
            this.getstuTem()
            this.getteacherTem()
        }
    }
</script>

<style scoped>
  .el-upload__tip{
    padding-top: 10px;
  }
  .el-dropdown-link {
    cursor: pointer;
    color: #ac1941;
  }
  .el-icon-arrow-down {
    font-size: 12px;
  }
  .personal{
    padding-left: 5px;
    padding-top: 10px;
    text-align: center;
  }
  .ruleForm{
    display: inline-block;
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
