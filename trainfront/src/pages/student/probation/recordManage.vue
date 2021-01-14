<template>
<div class="display">
  <span>提示：如果已提交过见习教学设计，但未在该页面显示，说明导师还未给予审阅意见，请之后再进行查看</span>
  <div class="data">
<table id="recordStastics">
  <tr>
    <th style="width:5%">序号</th>
    <th style="width:30%">记录类型</th>
    <th style="width:20%">文件名</th>
    <th style="width:20%">提交时间</th>
    <th style="width:25%">操作</th>
  </tr>
  <tr v-for="(item,index) in recordData">
    <td>{{index+1}}</td>
    <td v-if="item.kind==1">课堂教学观摩记录</td>
    <td v-if="item.kind==2">课堂教学观摩讨论记录</td>
    <td v-if="item.kind==3">班级管理见习记录</td>
    <td v-if="item.kind==4">教研活动见习记录</td>
    <td v-if="item.kind==5">见习生试讲教学设计</td>
    <td v-if="item.kind==6">见习生试讲听课记录</td>
    <td>{{item.filename}}</td>
    <td>{{item.createdTime}}</td>
    <td>
      <span>
        <el-button type="primary" icon="el-icon-search" circle @click="showRecord(item.uId)"></el-button>
        <el-button type="warning" size="small" plain v-show="item.kind==5" @click="showReview(item.createdTime)">导师意见</el-button>
      </span>
    </td>
  </tr>
</table>
  </div>
  <div class="count">
    <el-button type="text" @click="count" >记录数据统计</el-button>
    <el-dialog title="统计" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
      <table>
        <tr>
          <th>记录类型</th>
          <th>数据</th>
        </tr>
        <tr v-for="item in countData">
          <td>{{item.type}}</td>
          <td>{{item.num}}</td>
        </tr>
      </table>
   <span slot="footer" class="dialog-footer">
    <el-button @click="dialogVisible = false">取 消</el-button>
    <el-button type="primary" @click="dialogVisible = false">确 定</el-button>
  </span>
    </el-dialog>
  </div>
  <el-dialog title="指导教师审阅意见" :visible.sync="dialogFormVisible">
    <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="review">
    </el-input>
    <span slot="footer" class="dialog-footer">
    <el-button @click="dialogFormVisible = false">取 消</el-button>
    <el-button type="primary" @click="dialogFormVisible = false">确 定</el-button>
  </span>
  </el-dialog>
</div>
</template>

<script>
    export default {
        name: "recordManage",
        data(){
            return{
                username:'',
                review:'',//导师意见
                dialogVisible:false,
                dialogFormVisible:false,
                recordData:[],
                countData:[
                    {
                    kind:1,
                    type:'课堂教学观摩记录',
                    num:0
                   },
                    {
                     kind:2,
                     type:'课堂教学观摩讨论记录',
                     num:0
                    },
                    {
                     kind:3,
                     type:'班级管理见习记录',
                     num:0
                    },
                    {
                     kind:4,
                     type:'教研活动见习记录',
                     num:0
                    },
                    {
                     kind:5,
                     type:'见习生试讲教学设计',
                     num:0
                    },
                    {
                    kind:6,
                    type:'见习生试讲听课记录',
                    num:0
                    },
                ]
            }
        },
        methods:{
            getRecord(){
                //获取记录数据
                let that = this
                that.$http.post('/yii/record/record/getrecorddata',{
                    username:that.username,
                    type:1
                }).then(function(res){
                    console.log(res.data)
                    that.recordData=res.data.data
                })
            },
            showRecord(id){
                //查看记录pdf文件
                let that = this
                that.$http.post('/yii/record/record/getpdf',{
                    uId:id
                }).then((res)=>{
                    console.log(res.data.data)
                    var url='127.0.0.1/'+res.data.data
                    window.open('http://'+url,'_blank')
                })
            },
            showReview(time){
               //单独查看导师的评价意见
                let that = this
                that.$http.post('/yii/record/record/getreview',{
                    submitTime:time,
                    username:this.username
                }).then(function(res){
                    console.log(res.data)
                    that.dialogFormVisible=true
                    that.review=res.data.data.review
                })
            },
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        done();
                    })
                    .catch(_ => {});
            },
            count(){
                let that = this
                that.dialogVisible = true
                var data=that.recordData
                for ( let i =0;i<data.length;i++){
                    if(data[i].kind==1){
                        that.countData[0].num=that.countData[0].num+1
                    }else if(data[i].kind==2){
                        that.countData[1].num=that.countData[1].num+1
                    }else if(data[i].kind==3){
                        that.countData[2].num=that.countData[2].num+1
                    }else if(data[i].kind==4){
                        that.countData[3].num=that.countData[3].num+1
                    }else if(data[i].kind==5){
                        that.countData[4].num=that.countData[4].num+1
                    }else if(data[i].kind==6){
                        that.countData[5].num=that.countData[5].num+1
                    }
                }
            }
        },
        created() {
            this.username=this.$store.getters.getsName
            this.getRecord()
        }
    }
</script>

<style scoped="scoped" type="text/css">
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
