<template>
  <el-tabs v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="上传班主任工作日记" name="first">
      <div class="main">
        <div class="oneEvent">
          <el-form :model="diaryData" label-width="150px" ref="formData" class="demo" style="display: inline-block">
            <el-row
              v-for="(item,index) in diaryData.infoData"
              :key="index"
              style="border-bottom: 2px solid #f0f0f0;padding: 10px;width:800px"
            >
              <el-form-item label="日期" style="width:400px;">
                <el-date-picker
                  v-model="item.date"
                  type="date"
                  placeholder="选择日期"
                  value-format="yyyy-MM-dd">
                </el-date-picker>
              </el-form-item>
              <el-form-item label="记事" prop="desc">
                <el-input type="textarea" v-model="item.content"></el-input>
              </el-form-item>
              <el-button type="warning" size="small" plain @click="saveOne(item.date,item.content)">确定</el-button>
              <el-button type="danger" v-if="diaryData.infoData.length > 1" size="small" plain @click="removeRow(index)">删除</el-button>
            </el-row>
            <el-form-item class="actions">
              <el-button size="small" @click="addEvent">新增班主任工作日记</el-button>
            </el-form-item>
          </el-form>
        </div>
      </div>
    </el-tab-pane>
    <!--        修改日记-->
    <el-dialog title="修改班主任工作日记" :visible.sync="dialog">
      <el-form :model="eventForm">
        <el-form-item label="日期" style="width:400px;">
          <el-date-picker
            v-model="eventForm.date"
            type="date"
            placeholder="选择日期"
            value-format="yyyy-MM-dd">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="记事" style="width:600px;">
          <el-input type="textarea" v-model="eventForm.content"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" style="align-content: center" class="dialog-footer">
        <el-button type="primary" @click="updateE()">更新</el-button>
        <el-button @click="closeDialog()">取消</el-button>
      </div>
    </el-dialog>
    <el-tab-pane label="查看班主任工作日记" name="second">
      <div class="allevents">
        <table id="diaryStastics">
          <tr>
            <th style="width:10%">序号</th>
            <th style="width:15%">日期</th>
            <th style="width:70%">记事</th>
            <th style="width:5%">操作</th>
          </tr>
          <tr v-for="(item,index) in diaryList">
            <td>{{index+1}}</td>
            <td>{{item.date}}</td>
            <td>{{item.content}}</td>
            <td>
              <span style="cursor: pointer" @click="editEvent(item.username,item.dId)"><el-button type="danger" icon="el-icon-edit" circle></el-button></span>
            </td>
          </tr>
        </table>
      </div>
    </el-tab-pane>
  </el-tabs>

</template>

<script>
    export default {
        name: "workDiary",
        data(){
            return{
                activeName:'first',
                username:'',
                dialog:false,
                diaryData:{
                    infoData:[{
                        date: '',
                        content: ''
                    }]
                },
                eventForm:{
                    id:'',
                    date:'',
                    content:''
                },
                diaryList:[]
            }
        },
        methods:{
            handleClick(tab, event) {
                console.log(tab, event);
            },
            getAlldiarys(){
                //获取所有班主任工作日记
                let that = this
                that.$http.post('/yii/practice/event/getevents',{
                    username:this.username,
                    kind:2
                }).then(function(res){
                    console.log(res.data)
                    that.diaryList=res.data.data
                })
            },
            saveOne(date,content){
                //保存一次记事
                if(date==''||content==''){
                    alert('内容不能为空!')
                    return
                }
                this.$confirm("此操作将保存此条工作日记, 是否继续?", "提示", {
                    confirmButtonText: "确定",
                    cancelButtonText: "取消",
                    type: "warning"
                }).then(()=> {
                    let that= this
                    that.$http.post('/yii/practice/event/submitoneevent',{
                        username:this.username,
                        time:date,
                        content:content,
                        kind:2//代表班主任工作日记
                    }).then(function(res){
                        console.log(res.data)
                        if(res.data.message=="success"){
                            alert('信息已存储！')
                            this.getAlldiarys()
                        }else if(res.data.message=="信息已存在"){
                            alert('请勿重复提交信息！')
                        }else{
                            alert('信息存储失败！')
                        }
                    })
                }).catch((err)=>{
                    console.log(err)
                })
            },
            removeRow(index){
                //删除一项工作记事
                this.diaryData.infoData.splice(index,1)
            },
            addEvent(){
                this.diaryData.infoData.push({
                    date:'',
                    content:''
                })
            },
            editEvent(uname,id){
                //修改日记内容
                let that = this
                that.$http.post('/yii/practice/event/getoneevent',{
                    username:uname,
                    id:id
                }).then(function(res){
                    console.log(res.data)
                    that.eventForm.id=res.data.data.dId
                    that.eventForm.date=res.data.data.date
                    that.eventForm.content=res.data.data.content
                    that.dialog=true
                })

            },
            updateE(){
                let that = this
                that.$http.post('/yii/practice/event/editevent',{
                    username:that.username,
                    id:that.eventForm.id,
                    date:that.eventForm.date,
                    content:that.eventForm.content,
                }).then((res)=>{
                    console.log(res.data)
                    if(res.data.message=="success"){
                        alert('信息修改成功!')
                    }else{
                        alert('信息修改失败!')
                    }
                    that.getAlldiarys()
                    that.dialog=false
                })
            },
            closeDialog(){
                this.dialog=false
            }
        },
        created() {
            this.username=this.$store.getters.getsName
            this.getAlldiarys()
        }
    }
</script>

<style scoped="scoped" type="text/css">
@import '../../../common/css/tableLine.css';
.main{
  text-align: center;
}
.oneEvent{
  display: inline-block;
  text-align: center;
  color:white;
  width:80%;
  background-color: #a9ddec;
}
</style>
