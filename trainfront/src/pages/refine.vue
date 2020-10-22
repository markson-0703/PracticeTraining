<template>
<div class="display">
  <div class="display-header">
  <el-header style="background-color: white;width:80%;text-align: center">
  <span>
    <img src="../common/images/logo1.png">
    <el-button type="primary" style="background-color: #42b983;border:0px;width:100px;font-size:16px;float:right;margin:10px 20px 10px 0px" @click="gotoLogin()">去登录</el-button>
    <i class="el-icon-loading" style="float:right;color:darkorange;margin-top:20px"></i>
  </span>
  </el-header>
   <div class="notice">
    <h3 style="font-size: 28px">欢迎使用实习实训系统</h3>
    <p>在正式登录前请先完成你的个人信息！</p>
   </div>
  </div>
  <div class="displayShow">
<!--          本校教师-->
    <el-form :model="teacherDetails" :rules="rules" ref="teacherDetails" label-width="150px" style="margin: 50px auto;display: inline-block" v-show="s1">
    <el-form-item label="姓名" style="width:400px" prop="name">
      <el-input v-model="teacherDetails.name" autocomplete="off"></el-input>
     </el-form-item>
     <el-form-item label="工号" style="width:400px" prop="jno">
       <el-input v-model="teacherDetails.jno" autocomplete="off"></el-input>
     </el-form-item>
     <el-form-item label="联系电话" style="width:450px" prop="phone" >
       <el-input v-model="teacherDetails.phone" autocomplete="off"></el-input>
     </el-form-item>
      <el-form-item label="邮箱" style="width:450px" prop="email">
        <el-input v-model="teacherDetails.email" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="教师职称" style="width:450px" prop="rank">
        <el-input v-model="teacherDetails.rank" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="subTeacher()">提交信息</el-button>
        <el-button @click="editTeaInfo()">修改信息</el-button>
        <el-button @click="resetTea()">重置</el-button>
        <p class="submit-tip" style="color: #919191;margin-top:15px;"><i class="el-icon-lock"></i>您的信息已加密，请放心填写</p>
      </el-form-item>
    </el-form>
<!--    以下为本校教师信息修改-->
    <el-dialog title="个人信息修改" :visible.sync="dialogFormVisible1">
      <el-form :model="teacherDetails">
        <el-form-item label="姓名" style="width:400px" prop="name">
          <el-input v-model="teacherDetails.name" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="工号" style="width:400px" prop="jno">
          <el-input v-model="teacherDetails.jno" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="联系电话" style="width:450px" prop="phone" >
          <el-input v-model="teacherDetails.phone" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="邮箱" style="width:450px" prop="email">
          <el-input v-model="teacherDetails.email" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="教师职称" style="width:450px" prop="rank">
          <el-input v-model="teacherDetails.rank" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
        <div slot="footer" style="align-content: center" class="dialog-footer">
          <el-button type="primary" @click="saveTea()">保存</el-button>
          <el-button class="el-button" @click="resetTea()">重置</el-button>
          <el-button @click="closeDialog1()">取消</el-button>
        </div>
    </el-dialog>
<!--          学生-->
    <el-form  :model="studentDetails" :rules="rules" ref="studentDetails" label-width="150px" style="margin: 50px auto;display: inline-block" v-show="s2">
      <el-form-item label="姓名" style="width:400px" prop="name">
        <el-input v-model="studentDetails.name" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="学号" style="width:400px" prop="sno">
        <el-input v-model="studentDetails.sno" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="性别" prop="sex">
        <el-radio v-model="studentDetails.sex" label="1" >男</el-radio>
        <el-radio v-model="studentDetails.sex" label="2">女</el-radio>
      </el-form-item>
      <el-form-item label="班级代号" style="width:450px" prop="classId" >
        <el-input v-model="studentDetails.classId" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="班级名称" style="width:450px" prop="className">
        <el-input v-model="studentDetails.className" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="专业代号" style="width:450px" prop="majorId">
        <el-input v-model="studentDetails.majorId" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="专业名称" style="width:450px" prop="majorName">
        <el-input v-model="studentDetails.majorName" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="出生日期" style="width:450px" prop="bDate">
        <el-input v-model="studentDetails.bDate" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="联系方式" style="width:450px" prop="phone">
        <el-input v-model="studentDetails.phone" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="邮箱" style="width:450px" prop="email">
        <el-input v-model="studentDetails.email" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="subStudent()">提交信息</el-button>
        <el-button @click="editStuInfo()">修改信息</el-button>
        <el-button @click="resetStu()">重置</el-button>
      </el-form-item>
    </el-form>
<!--    以下为学生信息修改-->
    <el-dialog title="个人信息修改" :visible.sync="dialogFormVisible2">
      <el-form  :model="studentDetails">
        <el-form-item label="姓名" style="width:400px" prop="name">
          <el-input v-model="studentDetails.name" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="学号" style="width:400px" prop="sno">
          <el-input v-model="studentDetails.sno" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="性别" prop="sex">
          <el-radio v-model="studentDetails.sex" label="1" >男</el-radio>
          <el-radio v-model="studentDetails.sex" label="2">女</el-radio>
        </el-form-item>
        <el-form-item label="班级代号" style="width:450px" prop="classId" >
          <el-input v-model="studentDetails.classId" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="班级名称" style="width:450px" prop="className">
          <el-input v-model="studentDetails.className" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="专业代号" style="width:450px" prop="majorId">
          <el-input v-model="studentDetails.majorId" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="专业名称" style="width:450px" prop="majorName">
          <el-input v-model="studentDetails.majorName" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="出生日期" style="width:450px" prop="bDate">
          <el-input v-model="studentDetails.bDate" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="联系方式" style="width:450px" prop="phone">
          <el-input v-model="studentDetails.phone" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="邮箱" style="width:450px" prop="email">
          <el-input v-model="studentDetails.email" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" style="align-content: center" class="dialog-footer">
        <el-button type="primary" @click="saveStu()">保存</el-button>
        <el-button class="el-button" @click="resetStu()">重置</el-button>
        <el-button @click="closeDialog2()">取消</el-button>
      </div>
    </el-dialog>
<!--          校外教师-->
    <el-form :model="tutorDetails" :rules="rules" ref="tutorDetails" label-width="150px" style="margin: 50px auto;display: inline-block" v-show="s3">
      <el-form-item label="姓名" style="width:400px" prop="name">
        <el-input v-model="tutorDetails.name" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="所在学校" style="width:400px" prop="sName">
        <el-input v-model="tutorDetails.schName" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="工号" style="width:400px" prop="jno">
        <el-input v-model="tutorDetails.jno" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="联系电话" style="width:450px" prop="contactPhone" >
        <el-input v-model="tutorDetails.contactPhone" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="邮箱" style="width:450px" prop="email">
        <el-input v-model="tutorDetails.email" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="教师职称" style="width:450px" prop="rank">
        <el-input v-model="tutorDetails.rank" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="subTutor()">提交信息</el-button>
        <el-button @click="editTutInfo()">修改信息</el-button>
        <el-button @click="resetTutor()">重置</el-button>
      </el-form-item>
    </el-form>
<!--    以下为校外教师信息修改-->
    <el-dialog title="个人信息修改" :visible.sync="dialogFormVisible3">
    <el-form :model="tutorDetails">
      <el-form-item label="姓名" style="width:400px" prop="name">
        <el-input v-model="tutorDetails.name" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="所在学校" style="width:400px" prop="schName">
        <el-input v-model="tutorDetails.schName" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="工号" style="width:400px" prop="jno">
        <el-input v-model="tutorDetails.jno" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="联系电话" style="width:450px" prop="contactPhone" >
        <el-input v-model="tutorDetails.contactPhone" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="邮箱" style="width:450px" prop="email">
        <el-input v-model="tutorDetails.email" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="教师职称" style="width:450px" prop="rank">
        <el-input v-model="tutorDetails.rank" autocomplete="off"></el-input>
      </el-form-item>
    </el-form>
      <div slot="footer" style="align-content: center" class="dialog-footer">
        <el-button type="primary" @click="saveTut()">保存</el-button>
        <el-button class="el-button" @click="resetTutor()">重置</el-button>
        <el-button @click="closeDialog3()">取消</el-button>
      </div>
    </el-dialog>
  </div>

</div>
</template>

<script>
    import {isNull, isSno, isEmail, isPhone, isCharacter,isInteger} from '../common/js/validate' // 导入验证规则
    export default {
        name: "refine",
        data(){
            return{
                s1:false,//判断本校教师界面是否显示
                s2:false,//判断学生界面是否显示
                s3:false,//判断校外教师界面是否显示
                role:'',//用户注册的身份
                uName:'',//用户名
                dialogFormVisible1:false,
                dialogFormVisible2:false,
                dialogFormVisible3:false,
                //教师详细信息
                teacherDetails:{
                    id:'',
                    name:'',//教师姓名
                    jno:'',//教师工号
                    phone:'',//联系电话
                    email:'',
                    rank:''
                },
                studentDetails:{
                    //学生详细信息
                    id:'',
                    name:'',
                    sno:'',
                    sex:'1',//默认为男生
                    classId:'',//班级代号
                    className:'',//班级名称
                    majorId:'',//专业代号
                    majorName:'',//专业名称
                    bDate:'',//出生日期
                    phone:'',//
                    email:''
                },
                tutorDetails:{
                    //校外导师详细信息
                    id:'',
                    name:'',
                    schName:'',//所在学校
                    jno:'',
                    contactPhone:'',
                    email:'',
                    rank:''
                },
                rules:{
                    name:[
                        {required: true, message: '输入姓名', trigger: 'blur'},
                        {min: 2, max: 5, message: '长度在 2 到 5 个字符', trigger: 'blur'},
                        {validator: isCharacter, trigger: 'blur'}
                    ],
                    schName:[
                        {required: true, message: '输入所在学校名称', trigger: 'blur'},
                        {validator: isNull, trigger: 'blur'}
                    ],
                    jno:[
                        {required: true, message: '输入工号', trigger: 'blur'},
                        // {type: 'string', min: 5, message: '工号不允许小于5位', trigger: 'blur'},
                        {validator: isSno, trigger: 'blur'}
                    ],
                    sno:[
                        {required: true, message: '输入学号', trigger: 'blur'},
                        {type: 'string', min: 6, message: '学号不允许小于6位', trigger: 'blur'},
                        {validator: isSno, trigger: 'blur'}
                    ],
                    phone:[
                        {required: true, message: '请输入联系方式', trigger: 'blur'},
                        {validator: isPhone, trigger: 'blur'}
                    ],
                    contactPhone:[
                        {required: true, message: '请输入手机号码', trigger: 'blur'},
                        {validator: isPhone, trigger: 'blur'}
                    ],
                    email:[
                        {required: true, message: '请输入邮箱', trigger: 'blur'},
                        {validator: isEmail, trigger: 'blur'}
                    ],
                    rank:[
                        { required: true, message: '请输入职称', trigger: 'blur' },
                        // { min: 3, max: 5, message: '长度在 3 到 5 个字符', trigger: 'blur' },
                        {validator: isCharacter, trigger: 'blur'}
                    ],
                    sex:[
                        {required: true, message: '请选择性别', trigger: 'change'}
                    ],
                    classId:[
                        {required: true, message: '请输入班级代号', trigger: 'blur'},
                        {validator: isInteger, trigger: 'blur'}
                    ],
                    majorId:[
                        {required: true, message: '请输入专业代号', trigger: 'blur'},
                        {validator: isInteger, trigger: 'blur'}
                    ],
                    className:[
                        {required: true, message: '输入班级名称', trigger: 'blur'},
                        {validator: isNull, trigger: 'blur'}
                    ],
                    majorName: [
                        {required: true, message: '输入专业名称', trigger: 'blur'},
                        {validator: isNull, trigger: 'blur'}
                    ],
                    bDate:[
                        {required: true, message: '请输入出生日期', trigger: 'blur'},
                        {validator: isNull, trigger: 'blur'}
                    ]
                }
            }
        },
        methods:{
            isShow(){
                if(this.role=='2'){
                    //本校教师用户
                    this.s1=true;
                    this.s2=false;
                    this.s3=false;
                }else if(this.role=='3'){
                    //学生用户
                    this.s1=false;
                    this.s2=true;
                    this.s3=false;
                }else if(this.role=='4'){

                    //校外教师用户
                    this.s1=false;
                    this.s2=false;
                    this.s3=true;
                }
            },
            getTeadetail(){
                //获取本校教师提交的信息
                let that=this
                that.$http.post('/yii/refine/refine/getteadetail',{tId:that.teacherDetails.id})
                    .then((res)=>{
                        console.log(res.data)
                        that.teacherDetails.id=res.data.data.tId
                        that.teacherDetails.name=res.data.data.tName
                        that.teacherDetails.jno=res.data.data.job_num
                        that.teacherDetails.phone=res.data.data.contactPhone
                        that.teacherDetails.email=res.data.data.email
                        that.teacherDetails.rank=res.data.data.rank
                    })
            },
            getStudetail(){
                //获取学生提交的信息
                let that=this
                that.$http.post('/yii/refine/refine/getstudetail',{sId:that.studentDetails.id})
                    .then((res)=>{
                        console.log(res.data)
                        that.studentDetails.id=res.data.data.sId
                        that.studentDetails.name=res.data.data.sName
                        that.studentDetails.sno=res.data.data.sno
                        that.studentDetails.sex=res.data.data.sex
                        that.studentDetails.classId=res.data.data.cId
                        that.studentDetails.className=res.data.data.className
                        that.studentDetails.majorId=res.data.data.majorId
                        that.studentDetails.majorName=res.data.data.majorName
                        that.studentDetails.bDate=res.data.data.bornDate
                        that.studentDetails.phone=res.data.data.phone
                        that.studentDetails.email=res.data.data.email
                    })
            },
            gettutordetail(){
                //获取校外教师提交的信息
                let that=this
                that.$http.post('/yii/refine/refine/gettutordetail',{tId:that.tutorDetails.id})
                    .then((res)=>{
                        console.log(res.data)
                        that.tutorDetails.id=res.data.data.tId
                        that.tutorDetails.name=res.data.data.tName
                        that.tutorDetails.schName=res.data.data.school_name
                        that.tutorDetails.jno=res.data.data.job_num
                        that.tutorDetails.contactPhone=res.data.data.contactPhone
                        that.tutorDetails.email=res.data.data.email
                        that.tutorDetails.rank=res.data.data.rank
                    })
            },
            subTeacher(){
                //提交本校教师的信息
                let that=this
                that.$http.post('/yii/refine/refine/subtea',{
                    username:that.uName,
                    name:that.teacherDetails.name,
                    jno:that.teacherDetails.jno,
                    phone:that.teacherDetails.phone,
                    email:that.teacherDetails.email,
                    rank:that.teacherDetails.rank
                }).then((res)=>{
                    console.log(res.data)
                    // that.teacherDetails.id=res.data.data
                    var msg=res.data.message
                    if(msg=="提交成功"){
                        that.teacherDetails.id=res.data.data
                        that.getTeadetail()
                        alert("个人信息完善成功")
                    }else if(msg=="信息已提交"){
                        alert("个人信息已提交，请勿重复提交")
                    }else if(msg=="提交失败"){
                        alert("个人信息完善失败")
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            },
            subStudent(){
                //提交学生的信息
                let that = this
                that.$http.post('/yii/refine/refine/substu',{
                    username:that.uName,
                    name:that.studentDetails.name,
                    sno:that.studentDetails.sno,
                    sex:that.studentDetails.sex,
                    classId:that.studentDetails.classId,
                    className:that.studentDetails.className,
                    majorId:that.studentDetails.majorId,
                    majorName:that.studentDetails.majorName,
                    bDate:that.studentDetails.bDate,
                    phone:that.studentDetails.phone,
                    email:that.studentDetails.email
                }).then((res)=>{
                    console.log(res.data)
                    var msg=res.data.message
                    if(msg="success"){
                        that.studentDetails.id=res.data.data
                        that.getStudetail()
                        alert("信息已完善")
                    }else if(msg="信息已提交"){
                        alert("信息已提交，请勿重复提交")
                    }else if(msg="failure"){
                        alert("信息完善失败")
                    }
                })

            },
            subTutor(){
                //提交校外教师的信息
                let that=this
                that.$http.post('/yii/refine/refine/subtut',{
                    username:that.uName,
                    name:that.tutorDetails.name,
                    sName:that.tutorDetails.schName,
                    jno:that.tutorDetails.jno,
                    phone:that.tutorDetails.contactPhone,
                    email:that.tutorDetails.email,
                    rank:that.tutorDetails.rank
                }).then((res)=>{
                    console.log(res.data)
                    // that.teacherDetails.id=res.data.data
                    var msg=res.data.message
                    if(msg=="success"){
                        that.tutorDetails.id=res.data.data
                        that.gettutordetail()
                        alert("个人信息完善成功")
                    }else if(msg=="信息已提交"){
                        alert("个人信息已提交，请勿重复提交")
                    }else if(msg=="failure"){
                        alert("个人信息完善失败")
                    }
                }).catch(function (error) {
                    console.log(error)
                })

            },
            editTeaInfo(){
                //修改校内教师个人信息
                let that=this
                that.$http.post('/yii/refine/refine/edittea',{tId:that.teacherDetails.id})
                    .then((res)=>{
                        console.log(res.data)
                        that.teacherDetails.name=res.data.data.tName
                        that.teacherDetails.jno=res.data.data.job_num
                        that.teacherDetails.phone=res.data.data.contactPhone
                        that.teacherDetails.email=res.data.data.email
                        that.teacherDetails.rank=res.data.data.rank
                    })
                that.dialogFormVisible1=true
            },
            editTutInfo(){
                let that=this
                that.gettutordetail()
                that.dialogFormVisible3=true
            },
            editStuInfo(){
                let that=this
                that.getStudetail()
                that.dialogFormVisible2=true
            },
            saveTea(){
                //提交修改后的校内教师信息
                let that = this
                that.$http.post('/yii/refine/refine/altertea',{
                    tId:that.teacherDetails.id,
                    name:that.teacherDetails.name,
                    jno:that.teacherDetails.jno,
                    phone:that.teacherDetails.phone,
                    email:that.teacherDetails.email,
                    rank:that.teacherDetails.rank
                }).then((res)=>{
                    console.log(res.data)
                    if (res.data.message=="success"){
                        alert('个人信息修改成功')
                    }else if(res.data.message=="failure"){
                        alert('个人信息修改失败')
                    }else if(res.data.message=="用户信息不完善"){
                        alert('请先完善个人信息')
                    }
                    //修改完成之后要重新获取数据，并且关闭弹窗
                    that.getTeadetail();
                    that.dialogFormVisible1=false
                }).catch(function(error){
                    console.log(error)
                })
            },
            saveStu(){
                //提交修改后的学生信息
                let that=this
                console.log(that.studentDetails.id)
                that.$http.post('/yii/refine/refine/alterstu',{
                    sId:that.studentDetails.id,
                    name:that.studentDetails.name,
                    sno:that.studentDetails.sno,
                    sex:that.studentDetails.sex,
                    classId:that.studentDetails.classId,
                    className:that.studentDetails.className,
                    majorId:that.studentDetails.majorId,
                    majorName:that.studentDetails.majorName,
                    bDate:that.studentDetails.bDate,
                    phone:that.studentDetails.phone,
                    email:that.studentDetails.email
                }).then((res)=>{
                    console.log(res.data)
                    if (res.data.message=="success"){
                        alert('个人信息修改成功')
                    }else if(res.data.message=="failure"){
                        alert('个人信息修改失败')
                    }else if(res.data.message=="用户信息不完善"){
                        alert('请先完善个人信息')
                    }
                    that.getStudetail()
                    that.dialogFormVisible2=false
                }).catch(function (error) {
                    console.log(error)
                })
            },
            saveTut(){
                //保存校外教师修改的信息
                let that=this
                that.$http.post('/yii/refine/refine/altertut',{
                    tId:that.tutorDetails.id,
                    name:that.tutorDetails.name,
                    sName:that.tutorDetails.schName,
                    jno:that.tutorDetails.jno,
                    phone:that.tutorDetails.contactPhone,
                    email:that.tutorDetails.email,
                    rank:that.tutorDetails.rank
                }).then((res)=>{
                    console.log(res.data)
                    if (res.data.message=="success"){
                        alert('个人信息修改成功')
                    }else if(res.data.message=="failure"){
                        alert('个人信息修改失败')
                    }else if(res.data.message=="用户信息不完善"){
                        alert('请先完善个人信息')
                    }
                    that.gettutordetail();
                    that.dialogFormVisible3=false
                }).catch(function (error) {
                    console.log(error)
                })
            },
            resetTea(){
                //重置本校教师信息
                this.$refs.teacherDetails.resetFields();
            },

            resetStu(){
                ///重置学生信息
                this.$refs.studentDetails.resetFields();
            },
            resetTutor(){
                //重置校外老师信息
                this.$refs.tutorDetails.resetFields();
            },
            closeDialog1(){
                this.dialogFormVisible1=false
            },
            closeDialog2(){
                this.dialogFormVisible2=false
            },
            closeDialog3(){
                this.dialogFormVisible3=false
            },
            gotoLogin(){
                //跳转到登录页面
                this.$router.push({path:'/login',name:'Login'})
            }
        },
        created(){
        this.role=(this.$route.params.role).toString()
        this.uName=this.$route.params.username//获取用户名
        this.isShow()
        console.log(typeof(this.$route.params.role))//注意role的类型
       }
    }
</script>

<style scoped="scoped" type="text/css">
  .display{
    width:100%
  }
  .display-header{
    background-color: #353535;
    height:300px;
    padding-top: 40px;
    padding-left: 200px;
    text-align: center;
  }
  .display-header img{
     width:100px;
     height:100px;
     float:left;
     vertical-align: center;
     margin-top: -20px;
     margin-left: 20px;
  }
  .notice{
    color:#0ac3e2;
  }
  .notice h3{
    padding-top: 50px;
  }
  .notice p{
    line-height: 50px;
  }
  .displayShow{
    margin: 40px 0;
    border: 1px solid #ddd;
    width: 100%;
    height: 100%;
    box-sizing: border-box;
    text-align: center;
  }
</style>
