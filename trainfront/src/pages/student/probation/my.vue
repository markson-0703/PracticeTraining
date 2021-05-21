<template>
  <div class="personal">
    <!--      该页面属于学生见习部分的个人中心-->
    <el-tabs v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="个人信息" name="first">
      <h1 style="font-weight:bold;font-color:black">{{this.content}}</h1>
      <div class="displayShow">
        <el-form :model="myDetails" label-width="150px" id="demo">
          <el-form-item label="用户名" style="width:400px;" prop="username">
            <el-input v-model="myDetails.username" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="姓名" style="width:400px;" prop="sName">
            <el-input v-model="myDetails.sName" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="学号" style="width:400px;" prop="sno">
            <el-input v-model="myDetails.sno" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="性别" style="width:400px;" prop="sex">
            <el-input v-model="myDetails.sex" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="班级代号" style="width:400px;" prop="cId">
            <el-input v-model="myDetails.cId" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="班级名称" style="width:400px;" prop="className">
            <el-input v-model="myDetails.className" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="专业代号" style="width:400px;" prop="majorId">
          <el-input v-model="myDetails.majorId" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="专业名称" style="width:400px;" prop="majorName">
            <el-input v-model="myDetails.majorName" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="出生日期" style="width:400px;" prop="bornDate">
            <el-input v-model="myDetails.bornDate" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="联系方式" style="width:400px;" prop="phone">
            <el-input v-model="myDetails.phone" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="邮箱" style="width:400px;" prop="email">
            <el-input v-model="myDetails.email" autocomplete="off"></el-input>
          </el-form-item>
        </el-form>
        <el-button type="warning" style="width:250px;margin-top:20px;display: inline-block" @click="downloadPdf()">导出信息</el-button>
      </div>
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
  </el-tabs>
  </div>

</template>

<script>
  import htmlToPdf from"../../utils/htmlToPdf"
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
                activeName: 'first',
                content:'欢迎您，学生用户',
                formLabelWidth:'120px',
                myForm:{
                    username:'',
                    pass: '',
                    checkPass: '',
                    old:''
                },
                myDetails:{
                    username:'',
                    sName:'',
                    sno:'',
                    sex:'',
                    cId:'',
                    className:'',
                    majorId:'',
                    majorName:'',
                    bornDate:'',
                    phone:'',
                    email:''
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
            load(){
                let data = {
                    username:this.myForm.username,
                    oldPassword: this.myForm.old,
                    newPassword: this.myForm.pass
                }
                console.log(data)
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
            showInfo(){
                //展示个人信息
                let that = this
                that.$http.post('/yii/probation/infomation/getstudetail',{username:this.myForm.username})
                    .then((res)=>{
                        console.log(res.data)
                        that.myDetails.username=res.data.data.username,
                        that.myDetails.sName=res.data.data.sName,
                        that.myDetails.sno=res.data.data.sno,
                        that.myDetails.cId=res.data.data.cId,
                        that.myDetails.className=res.data.data.className,
                        that.myDetails.majorId=res.data.data.majorId,
                        that.myDetails.majorName=res.data.data.majorName,
                        that.myDetails.bornDate=res.data.data.bornDate,
                        that.myDetails.phone=res.data.data.phone,
                        that.myDetails.email=res.data.data.email
                        if (res.data.data.sex=== '1') {
                            that.myDetails.sex = '男'
                        } else {
                            that.myDetails.sex = '女'
                        }
                    })
            },
            downloadPdf(){
                //导出pdf
                htmlToPdf.downloadPDF(document.querySelector('#demo'), '个人信息表')
            },
            handleClick(tab,event){
                console.log(tab,event)
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
            }
        },
        created() {
            console.log(this.$store.getters.getsName)
            this.myForm.username=this.$store.getters.getsName
            this.showInfo()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  .personal{
    padding-left: 5px;
    padding-top: 10px;
    text-align: center;
    vertical-align: center;
  }
  .ruleForm{
    display: inline-block;
  }
  .displayShow {
    margin:0px auto;
    color:#0f0f0f;
    border: solid 1px #e0e0e0;
    height: 100%;
    text-align: center;
    width: 100%;
    padding:30px 5px 5px 5px;
    background-color: skyblue;
    display: inline-block;
    justify-content: space-around;
    align-items:center;
  }
  #demo {
    background-color: #fff;
    width: 90%;
    /* height: 400px; */
    padding: 60px 0px 60px 400px;
    box-sizing: border-box;
    text-align: center;
    align-items: center;
    display:inline-block;
  }
</style>
