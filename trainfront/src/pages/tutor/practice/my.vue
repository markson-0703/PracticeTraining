<template>
  <div class="personal">
    <el-tabs v-model="activeName" @tab-click="handleClick">
      <el-tab-pane label="个人信息" name="first">
        <h1 style="font-weight:bold;font-color:black">{{this.content}}</h1>
        <div class="displayShow">
          <el-form :model="Details" label-width="150px" id="tutorInfo">
            <el-form-item label="姓名" style="width:400px" prop="name">
              <el-input v-model="Details.name" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="所在学校" style="width:400px" prop="sName">
              <el-input v-model="Details.schName" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="工号" style="width:400px" prop="jno">
              <el-input v-model="Details.jno" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="联系电话" style="width:450px" prop="contactPhone" >
              <el-input v-model="Details.contactPhone" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="邮箱" style="width:450px" prop="email">
              <el-input v-model="Details.email" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="教师职称" style="width:450px" prop="rank">
              <el-input v-model="Details.rank" autocomplete="off"></el-input>
            </el-form-item>
          </el-form>
          <div class="download">
            <el-button type="warning" style="width:200px; margin-top:20px;display: inline-block" @click="downloadPdf()">导出信息</el-button>
          </div>
        </div>
      </el-tab-pane>
      <el-tab-pane label="修改密码" name="second">
        <div class="displayShow">
          <el-form :model="pwdForm" status-icon :rules="rules" ref="pwdForm" label-width="100px" class="ruleForm">
            <el-form-item label="旧密码" style="width:400px" prop="old">
              <el-input type="password" v-model="pwdForm.old" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="新密码" style="width:400px" prop="pass">
              <el-input type="password" v-model="pwdForm.pass" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="确认密码" style="width:400px" prop="checkPass">
              <el-input type="password" v-model="pwdForm.checkPass" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="submitForm('pwdForm')">提交</el-button>
            </el-form-item>
          </el-form>
        </div>
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
                    if (this.pwdForm.checkPass !== '') {
                        this.$refs.pwdForm.validateField('checkPass')
                    }
                    callback()
                }
            }
            var validatePass2 = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请再次输入密码'))
                } else if (value !== this.pwdForm.pass) {
                    callback(new Error('两次输入密码不一致!'))
                } else {
                    callback()
                }
            }
            return{
                activeName: 'first',
                content:'欢迎您，教师用户',
                Details:{
                    username:'',//用户名
                    name:'',
                    schName:'',//所在学校
                    jno:'',
                    contactPhone:'',
                    email:'',
                    rank:''
                },
                pwdForm:{
                    username:'',
                    pass: '',
                    checkPass: '',
                    old:''
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
            },
            myInfo(){
                //校外导师个人信息
                let that = this
                that.$http.post('/yii/practice/detail/tutordetail',{
                    username:that.Details.username
                }).then(function(res){
                    console.log(res.data)
                    that.Details.username=res.data.data.username
                    that.Details.name=res.data.data.tName
                    that.Details.schName=res.data.data.school_name
                    that.Details.jno=res.data.data.job_num
                    that.Details.contactPhone=res.data.data.contactPhone
                    that.Details.email=res.data.data.email
                    that.Details.rank=res.data.data.rank
                })
            },
            load(){
                let data = {
                    username:this.pwdForm.username,
                    oldPassword: this.pwdForm.old,
                    newPassword: this.pwdForm.pass
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
            downloadPdf(){
                htmlToPdf.downloadPDF(document.querySelector('#tutorInfo'), '个人信息表')
            }
        },
        created() {
            this.Details.username=this.$store.getters.getsName//获取用户名
            this.pwdForm.username=this.$store.getters.getsName//获取用户名
            this.myInfo()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  .personal{
    margin:0 auto;
    padding-left: 5px;
    padding-top: 10px;
    text-align: center;
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
  #tutorInfo {
    background-color: #fff;
    width: 80%;
    padding: 60px 0px 60px 400px;
    box-sizing: border-box;
    text-align: center;
    align-items: center;
    display:inline-block;
  }
  .download{
    text-align: center;
  }
</style>
