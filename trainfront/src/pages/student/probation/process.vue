<template>
  <div class="processShow">
    <div class="oneActivity">
      <el-form :model="formData" label-width="150px" ref="formData" class="demo" style="display: inline-block">
        <el-row
          v-for="(item,index) in formData.infoData"
          :key="index"
          style="border-bottom: 1px solid #f0f0f0;padding: 10px;"
        >
          <el-form-item label="活动名称" style="width:400px;">
            <el-select v-model="item.content" size="small" placeholder="请选择实践活动" @change="getValue($event)">
              <el-option v-for="item1 in formData.activityList" :label="item1.content" :key="item1.contentId" :value="item1.contentId"></el-option>
            </el-select>
          </el-form-item>

          <el-form-item label="开始日期" style="width:400px;">
            <el-date-picker
              v-model="item.start_time"
              type="date"
              placeholder="选择日期"
              value-format="yyyy-MM-dd">
            </el-date-picker>
          </el-form-item>

          <el-form-item label="结束日期" style="width:400px;">
            <el-date-picker
              v-model="item.end_time"
              type="date"
              placeholder="选择日期"
              value-format="yyyy-MM-dd">
            </el-date-picker>
          </el-form-item>
          <el-button type="warning" size="small" plain @click="saveOne(item.start_time,item.end_time)">确定</el-button>
          <el-button type="danger" v-if="formData.infoData.length > 1" size="small" plain @click="removeRow(index)">删除</el-button>
        </el-row>
        <el-form-item class="actions">
          <el-button size="small" @click="addProcess">新增实践历程</el-button>
          <el-button type="primary" @click="processSubmit" size="small" style="background-color:#839206">保存</el-button>
        </el-form-item>
      </el-form>
    </div>
<!--    修改活动时间-->
    <el-dialog title="修改时间信息" :visible.sync="dialogFormVisible">
      <el-form :model="timeForm">
        <el-form-item label="开始日期" style="width:400px;">
          <el-date-picker
            v-model="timeForm.startTime"
            type="date"
            placeholder="选择日期"
            value-format="yyyy-MM-dd">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="结束日期" style="width:400px;">
          <el-date-picker
            v-model="timeForm.endTime"
            type="date"
            placeholder="选择日期"
            value-format="yyyy-MM-dd">
          </el-date-picker>
        </el-form-item>
      </el-form>
      <div slot="footer" style="align-content: center" class="dialog-footer">
        <el-button type="primary" @click="saveT()">更新</el-button>
        <el-button @click="closeDialog()">取消</el-button>
      </div>
    </el-dialog>
    <div class="allActivity">
<!--      显示学生已提交的所有教学进程-->
      <table id="activityStastics">
        <tr>
          <th>序号</th>
          <th>姓名</th>
          <th>学号</th>
          <th>实践活动编号</th>
          <th>实践内容</th>
          <th>开始时间</th>
          <th>结束时间</th>
          <th>时间调整</th>
        </tr>
        <tr v-for="(item,index) in exerciseList">
          <td>{{index+1}}</td>
          <td>{{item.sName}}</td>
          <td>{{item.sno}}</td>
          <td>{{item.contentId}}</td>
          <td>{{item.content}}</td>
          <td>{{item.startTime}}</td>
          <td>{{item.endTime}}</td>
          <td>
            <span style="cursor: pointer" @click="getEdittime(item.username,item.contentId)"><el-button type="primary" icon="el-icon-edit" circle></el-button></span>
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
    export default {
        name: "process",
        data(){
            return{
                formData:{
                    activityList:[],
                    infoData:[{
                        start_time: '',
                        end_time: '',
                        content: ''
                    }]
                },
                exerciseList:[],//存放该学生的所有见习活动
                username:'',//学生的用户名
                sno:'',
                sName:'',
                currentActivity:'',//当前下拉框选中的实践活动
                currentAid:0,//当前选中的实践活动的Id
                dialogFormVisible:false,
                timeForm:{
                    startTime:'',//开始时间
                    endTime:''//结束时间
                }
            }
        },
        methods:{
            getactivityData(){
                //获取教学实践过程中的所有活动
                let that = this
                that.$http.post('/yii/probation/infomation/getactivity')
                    .then((res)=>{
                       console.log(res.data)
                        this.formData.activityList=res.data.data
                    })
            },
            saveT(){
               //保存更新的时间数据
               let that = this
               that.$http.post('/yii/probation/process/edittime',{
                   username:that.username,
                   contentId:that.currentAid,
                   start:that.timeForm.startTime,
                   end:that.timeForm.endTime,
               }).then((res)=>{
                   console.log(res.data)
                   if(res.data.message=="success"){
                       alert('信息修改成功!')
                   }else{
                       alert('信息修改失败!')
                   }
                   that.getallActivities()
                   that.dialogFormVisible=false
               })
            },
            closeDialog(){
                this.dialogFormVisible=false
            },
            removeRow(index){
                this.formData.infoData.splice(index,1)
            },
            addProcess(){
                // this.formData.activityList.push()
                this.formData.infoData.push({
                    start_time:'',
                    end_time:'',
                    content:''
                })
            },
            getValue(id){
                //获取下拉框的值
                let obj = {};
                obj = this.formData.activityList.find((option)=>{//这里的gradeList就是上面遍历的数据源
                    return option.contentId === id;//筛选出匹配数据
                });
                console.log(obj.content);//获取的具体内容
                console.log(obj.contentId);//获取的实践活动Id
                this.currentAid=obj.contentId
                this.formData.infoData.content=obj.contentId
            },
            saveOne(start,end){
                //点击确定的时候将当前数据先存储
                this.$confirm("此操作将保存当前见习信息, 是否继续?", "提示", {
                    confirmButtonText: "确定",
                    cancelButtonText: "取消",
                    type: "warning"
                }).then(()=>{
                    let that = this
                    that.$http.post('/yii/probation/process/submitoneprocess',{
                        username:that.username,
                        sno:that.sno,
                        sName:that.sName,
                        contentId:that.currentAid,
                        startTime:start,
                        endTime:end
                    }).then((res)=>{
                        console.log(res.data)
                        if(res.data.message=="success"){
                            alert('信息已存储！')
                            that.getallActivities()
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
            processSubmit(){
               //
            },
            getEdittime(username,id){
                //调整活动时间
                let that = this
                that.dialogFormVisible=true
                that.$http.post('/yii/probation/infomation/gettime',{
                    username:username,
                    contentId:id
                }).then((res)=>{
                    console.log(res.data)
                    that.currentAid=res.data.data.contentId
                    that.timeForm.startTime=res.data.data.startTime
                    that.timeForm.endTime=res.data.data.endTime
                })

            },
            getpersonInfo(){
                //根据学生用户名获取个人信息
                let that = this
                that.$http.post('/yii/probation/infomation/getstudetail',{
                    username:that.username
                }).then((res)=>{
                    console.log(res.data)
                    that.sno=res.data.data.sno;
                    that.sName=res.data.data.sName
                })
            },
            getallActivities(){
                //获取所有活动
                let that = this
                that.$http.post('/yii/probation/infomation/getallactivity',{
                    username:that.username,
                    type:1
                }).then((res)=>{
                    console.log(res.data)
                    that.exerciseList=res.data.data
                })
            }
        },
        created() {
            this.username=this.$store.getters.getsName//当前学生的用户名
            this.getactivityData()
            this.getpersonInfo()
            this.getallActivities()
            console.log(this.$store.getters.getsName)
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import "../../../common/css/pagination.css";
.oneActivity{
  text-align: center;
  color:white;
  width:45%;
  float:left;
  background-color: cornflowerblue;
}
.allActivity{
  width:50%;
  text-align: center;
  float:left;
  padding-left: 30px;
}
.processShow{
  width:100%;
  /*height:700px;*/
  /*background-color:lightskyblue;*/
  }
  th{
    color:white
  }

</style>
