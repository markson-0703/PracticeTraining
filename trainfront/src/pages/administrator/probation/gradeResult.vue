<template>
  <div class="display">
    <table>
      <tr>
        <th style="width:10%">序号</th>
        <th style="width:15%">见习生姓名</th>
        <th style="width:15%">校内指导</th>
        <th style="width:15%">见习学校</th>
        <th style="width:10%">见习年级</th>
        <th style="width:10%">校外指导</th>
        <th style="width:10%">见习组长</th>
        <th style="width:15%">见习成绩</th>
      </tr>
      <tr v-for="(item,index) in markInfo">
       <td>{{index+1}}</td>
       <td>{{item.sName}}</td>
       <td>{{item.teacher}}</td>
       <td>{{item.school}}</td>
       <td>{{item.grade}}</td>
       <td>{{item.tutor}}</td>
       <td>{{item.leader}}</td>
        <td>{{item.mark}}
          <el-button type="danger" plain size="small" @click="detail(item.username)">详情</el-button>
        </td>
      </tr>
    </table>
<!--    见习成绩详情-->
    <el-dialog title="见习成绩详情" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
      <table>
        <tr>
          <th>行为表率</th>
          <th>课堂教学见习</th>
          <th>参与班级管理</th>
          <th>参与教研活动</th>
          <th>试讲</th>
          <th>见习报告</th>
        </tr>
        <tr>
          <td>{{this.dataList.mark1}}</td>
          <td>{{this.dataList.mark2}}</td>
          <td>{{this.dataList.mark3}}</td>
          <td>{{this.dataList.mark4}}</td>
          <td>{{this.dataList.mark5}}</td>
          <td>{{this.dataList.mark6}}</td>
        </tr>
      </table>
      <span slot="footer" class="dialog-footer">
    <el-button @click="dialogVisible = false">取 消</el-button>
    <el-button type="primary" @click="dialogVisible = false">确 定</el-button>
  </span>
    </el-dialog>
  </div>
</template>

<script>
    export default {
        name: "gradeResult",
        data(){
            return{
                markInfo:[],
                dialogVisible:false,
                dataList:[]
            }
        },
        methods:{
            markData(){
                let that = this
                that.$http.post('/yii/evaluation/mark/markdata')
                    .then(function(res){
                        console.log(res.data)
                        that.markInfo=res.data.data
                    })
            },
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        done();
                    })
                    .catch(_ => {});
            },
            detail(uname){
                let that = this
                that.$http.post('/yii/evaluation/mark/markdetail',{
                    uname:uname
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.dialogVisible=true
                        that.dataList=res.data.data
                        console.log(that.dataList)
                    }
                })
            }
        },
        created() {
         this.markData()
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
