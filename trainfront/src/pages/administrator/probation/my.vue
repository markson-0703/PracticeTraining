<template>
    <div class="personal">
<!--      该页面属于管理员的个人中心-->
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
                activeName: 'first',
                content:'欢迎您，管理员',
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
        }
    }
</script>

<style scoped="scoped" type="text/css">
.personal{
  padding-left: 5px;
  padding-top: 10px;
  text-align: center;
}
.ruleForm{
display: inline-block;
  }
</style>
