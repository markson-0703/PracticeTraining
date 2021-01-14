<template>
<div class="display">
  <table>
    <tr>
      <th style="width:3%">序号</th>
      <th style="width:7%">见习生姓名</th>
      <th style="width:10%">校内指导</th>
      <th style="width:15%">见习学校</th>
      <th style="width:10%">见习年级</th>
      <th style="width:10%">校外指导</th>
      <th style="width:10%">见习组长</th>
      <th style="width:15%">评定时间</th>
      <th style="width:20%">操作</th>
    </tr>
    <tr v-for="(item,index) in assessInfo">
      <td>{{index+1}}</td>
      <td>{{item.sName}}</td>
      <td>{{item.teacher}}</td>
      <td>{{item.school}}</td>
      <td>{{item.grade}}</td>
      <td>{{item.tutor}}</td>
      <td>{{item.leader}}</td>
      <td>{{item.finishTime}}</td>
      <td>
        <span>
           <el-tag type="info" dark v-show="item.status<4">评定环节还未结束</el-tag>
           <el-button type="danger" plain size="small" v-show="item.status==4" @click="review(item.username)">审阅</el-button>
           <el-tag type="succes" dark  v-show="item.status==5">已审阅</el-tag>
           <el-button type="primary" plain size="small" @click="checkAssess(item.username)" v-show="item.status>3">查看</el-button>
        </span>
      </td>
    </tr>
  </table>
</div>
</template>

<script>
    export default {
        name: "evaluationResult",
        data(){
            return{
                assessInfo:[]
            }
        },
        methods:{
            evaluationData(){
                //获取评价数据
                let that = this
                that.$http.post('/yii/evaluation/evaluation/evaluationdata')
                    .then(function(res){
                        console.log(res.data)
                        that.assessInfo=res.data.data
                    })
            },
            checkAssess(uname){
                //查看最终评定结果
                let that = this
                that.$http.post('/yii/evaluation/evaluation/finalevaluation',{
                    username:uname
                }).then(function(res){
                    console.log(res.data)
                    var url='127.0.0.1/'+res.data.data
                    window.open('http://'+url)
                }).catch(function(err){
                    console.log(err)
                })
            },
            review(uname){
                //管理员审核评定结果
                let that = this
                that.$http.post('/yii/evaluation/evaluation/checkevaluation',{
                    username:uname
                }).then(function(res){
                    console.log(res.data)
                    //更新状态
                    that.evaluationData()
                })
            }
        },
        created() {
            this.evaluationData()
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
