<template>
  <div class="display">
  <el-tabs v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="评分细则" name="first" style="background-color: #f0f0f0;">
      <scale></scale>
    </el-tab-pane>
    <el-tab-pane label="见习成绩" name="second" v-show="this.flag"  style="display: inline-block;">
      <el-form :model="markForm">
        <el-form-item label="总成绩" :label-width="formLabelWidth">
          <el-input v-model="markForm.totalMark"></el-input>
        </el-form-item>
        <el-form-item label="行为表率部分" :label-width="formLabelWidth">
          <el-input v-model="markForm.mark1"></el-input>
        </el-form-item>
        <el-form-item label="课堂教学见习部分" :label-width="formLabelWidth">
          <el-input v-model="markForm.mark2"></el-input>
        </el-form-item>
        <el-form-item label="参与班级管理部分" :label-width="formLabelWidth">
          <el-input v-model="markForm.mark3"></el-input>
        </el-form-item>
        <el-form-item label="参与教研活动部分" :label-width="formLabelWidth">
          <el-input v-model="markForm.mark4"></el-input>
        </el-form-item>
        <el-form-item label="试讲部分" :label-width="formLabelWidth">
          <el-input v-model="markForm.mark5"></el-input>
        </el-form-item>
        <el-form-item label="见习报告部分" :label-width="formLabelWidth">
          <el-input v-model="markForm.mark6"></el-input>
        </el-form-item>
      </el-form>
    </el-tab-pane>
  </el-tabs>
  </div>
</template>

<script>
    import Scale from "../../../components/scale";
    export default {
        name: "markResult",
        components: {Scale},
        data(){
            return{
             activeName: 'first',
             username:'',//学生的用户名
             formLabelWidth: '150px',
              flag:false,
             markForm:{
                 totalMark:'',
                 mark1:'',
                 mark2:'',
                 mark3:'',
                 mark4:'',
                 mark5:'',
                 mark6:''
             }
            }
        },
        methods:{
            handleClick(tab, event) {
                console.log(tab, event);
            },
            markDeatil(){
                //获取分数详情
                let that = this
                that.$http.post('/yii/evaluation/mark/markdetail',{
                    uname:that.username
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=='success'){
                        that.flag=true
                        that.markForm.totalMark=res.data.data.totalmark
                        that.markForm.mark1=res.data.data.mark1
                        that.markForm.mark2=res.data.data.mark2
                        that.markForm.mark3=res.data.data.mark3
                        that.markForm.mark4=res.data.data.mark4
                        that.markForm.mark5=res.data.data.mark5
                        that.markForm.mark6=res.data.data.mark6
                    }
                })
            }
        },
        created() {
           this.username=this.$store.getters.getsName
            this.markDeatil()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  .display{
    text-align: center;
  }
</style>
