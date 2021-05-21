<template>
    <div class="display">
      <table id="theirRecords">
        <tr>
          <th style="width:5%">序号</th>
          <th style="width:10%">学号</th>
          <th style="width:10%">学生姓名</th>
          <th style="width:10%">见习记录数据</th>
          <th style="width:10%">教学设计</th>
          <th style="width:15%">审阅状态</th>
        </tr>
        <tr v-for="(item,index) in infoData ">
          <td>{{index+1}}</td>
          <td>{{item.sno}}</td>
          <td>{{item.sName}}</td>
          <td>
            {{item.record}}条数据
            <span><el-button size="small" type="primary" plain @click="recordDetail(item.username,item.sName)">详情</el-button></span>
          </td>
          <td>
            {{item.design}}条数据
            <span><el-button size="small" type="primary" plain @click="designDetail(item.username)" v-show="item.status!=2">详情</el-button></span>
          </td>
          <td>
          <span>
            <el-tag type="success" v-show="item.status==1">已审阅</el-tag>
            <el-tag type="info" v-show="item.status==0">待审阅</el-tag>
            <el-tag type="warning" v-show="item.status==2">暂未有提交</el-tag>
          </span>
          </td>
        </tr>
      </table>
<!--      显示记录详情-->
      <el-dialog title="见习记录详情" :visible.sync="dialogVisible" :before-close="handleClose" width="800px">
        <span><el-button type="danger" plain @click="bulkDownload">批量下载</el-button></span>
        <table>
          <tr>
            <th style="width:5%">Id号</th>
            <th style="width:30%">类型</th>
            <th style="width:23%">文件名称</th>
            <th style="width:27%">上传时间</th>
            <th style="width:15%">操作</th>
          </tr>
          <tr v-for="item in recordData">
            <td>{{item.uId}}</td>
            <td v-if="item.kind==1">课堂教学观摩记录</td>
            <td v-if="item.kind==2">课堂教学观摩讨论记录</td>
            <td v-if="item.kind==3">班级管理见习记录</td>
            <td v-if="item.kind==4">教研活动见习记录</td>
            <td v-if="item.kind==5">见习生试讲教学设计</td>
            <td v-if="item.kind==6">见习生试讲听课记录</td>
            <td>{{item.filename}}</td>
            <td>{{item.createdTime}}</td>
            <td>
              <span style="display:flex">
                <button style="border: 0px;float:left;margin-left:10px;cursor: pointer" @click="theirRecord(item.uId)">查看与下载</button>
              </span>
            </td>
          </tr>
        </table>
       <span slot="footer" class="dialog-footer">
         <el-tag size="small" v-for="(number,index) in type" :key="index" style="margin:0px 5px">
           记录类型{{number.id}}:{{number.num}}
         </el-tag>
       <el-button @click="dialogVisible = false">取 消</el-button>
       <el-button type="primary" @click="dialogVisible = false">确 定</el-button>
       </span>
      </el-dialog>
      <!--      显示教学设计详情-->
      <el-dialog title="教学设计详情" :visible.sync="dialogVisible1" :before-close="handleClose" width="800px">
        <table>
          <tr>
            <th>id号</th>
            <th>提交时间</th>
            <th>详情</th>
            <th>提交审阅</th>
          </tr>
          <tr v-for="item in instructionData">
            <td>{{item.id}}</td>
            <td>{{item.submitTime}}</td>
            <td>
              <span><button style="border: 0px;cursor: pointer" @click="detail(item.id)">详细内容</button></span>
            </td>
            <td>
              <span>
                <button style="border: 0px;cursor: pointer" v-if="item.review==null" @click="review(item.id)">审阅</button>
                <el-tag size="small" v-if="item.review!=null">已审阅</el-tag>
              </span>
            </td>
          </tr>
        </table>
        <span slot="footer" class="dialog-footer">
       <el-button @click="dialogVisible1 = false">取 消</el-button>
       <el-button type="primary" @click="dialogVisible1 = false">确 定</el-button>
       </span>
      </el-dialog>
<!--    提交审阅意见-->
      <el-dialog title="审阅意见" :visible.sync="dialogVisible2" :before-close="handleClose">
        <el-form :model="instructionForm">
        <el-form-item label="教学设计审阅意见">
          <el-input type="textarea" v-model="instructionForm.currentReview"></el-input>
        </el-form-item>
        </el-form>
        <span slot="footer" class="dialog-footer">
       <el-button @click="dialogVisible2 = false">取 消</el-button>
       <el-button type="primary" @click="submit(instructionForm.currentReview)">提交</el-button>
       </span>
      </el-dialog>
    </div>
</template>

<script>
    import { quillEditor } from 'vue-quill-editor'
    import * as Quill from 'quill'  //引入编辑器
    //quill编辑器的字体
    var fonts = ['SimSun', 'SimHei','Microsoft-YaHei','KaiTi','FangSong','Arial','Times-New-Roman','sans-serif'];
    var Font = Quill.import('formats/font');
    Font.whitelist = fonts; //将字体加入到白名单
    Quill.register(Font, true);
    import axios from 'axios'
    import JSZip from 'jszip'
    import FileSaver from 'file-saver'
    export const getFile =(url) =>{
        return new Promise((resolve, reject) => {
            axios({
                method:'get',
                url,
                responseType: 'arraybuffer'
            }).then(data => {
                resolve(data.data)
            }).catch(error => {
                reject(error.toString())
            })
        })
    }
    export default {
        name: "record",
        data(){
            return{
                username:'',//该教师的用户名
                stuname:'',//当前学生的姓名
                infoData:[],
                recordData:[],//记录数据
                instructionData:[],//教学设计数据
                orderAttachment: [],
                dialogVisible:false,
                dialogVisible1:false,
                dialogVisible2:false,
                instructionForm:{
                    currentReview:'',//当前审阅意见
                },
                currentId:'',
                type:[
                    {   id:1,
                        name:'课堂教学观摩记录',
                        num:0
                    },
                    {   id:2,
                        name:'课堂教学观摩讨论记录',
                        num:0
                    },
                    {   id:3,
                        name:'班级管理见习记录',
                        num:0
                    },
                    {   id:4,
                        name:'教研活动见习记录',
                        num:0
                    },
                    {   id:5,
                        name:'见习生试讲教学设计',
                        num:0
                    },
                    {   id:6,
                        name:'见习生试讲听课记录',
                        num:0
                    }
                ],
            }
        },
        methods:{
            getInfo(){
                //获取学生基础信息
                let that = this
                that.$http.post('/yii/resource/resource/studentinfo',{
                    username:that.username
                }).then(function(res){
                    console.log(res.data.data)
                    if(res.data.message=="success"){
                        that.infoData=res.data.data
                    }
                })
            },
            recordDetail(uname,name){
             //动态获取学生记录详情
                let that = this
                that.stuname=name
                that.$http.post('/yii/resource/resource/recordinfo',{
                    username:uname,
                    headers:{}
                }).then(function(res){
                    console.log(res.data)
                    that.dialogVisible=true
                    that.recordData=res.data.data[0]
                    for(var b =0;b<res.data.data[1].length;b++){
                        that.orderAttachment[b]='/zip'+res.data.data[1][b].attachment
                    }
                    for(let a=0;a<6;a++){
                        that.type[a].num=0
                    }
                    for(let i=0; i<that.recordData.length;i++){
                        var j=that.recordData[i].kind
                        that.type[j-1].num=that.type[j-1].num+1
                    }
                })
            },
            designDetail(uname){
                //获取学生教学设计详情
                let that = this
                that.$http.post('/yii/resource/resource/instructioninfo',{
                    username:uname
                }).then(function(res){
                    console.log(res.data)
                    that.dialogVisible1=true
                    that.instructionData=res.data.data
                })
            },
            theirRecord(id){
                //查看并下载当前记录
                let that = this
                that.$http.post('/yii/resource/resource/geturl',{
                    uId:id
                }).then(function(res){
                    console.log(res.data.data)
                    var url='127.0.0.1/'+res.data.data
                    var href='http://'+url
                    window.open(href,'_blank')
                })
            },
            submit(content){
                console.log(this.currentId)
                let that = this
                that.$http.post('/yii/resource/resource/submitreview',{
                    id:that.currentId,
                    content:content
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        alert('审阅成功!')
                        //更新一下记录数据
                        that.getInfo()
                        that.designDetail(res.data.data)

                    }else{
                        that.$message.error(res.data.message);
                    }
                    that.dialogVisible2=false
                })

            },
            review(id){
                console.log(id)
                this.dialogVisible2=true
                this.currentId=id
            },

            detail(id){
                this.$router.push({name:'Instruction',params:{id:id}})
            },
            bulkDownload(){
                //批量下载
                const data=this.orderAttachment
                const zip = new JSZip()
                const cache = {}
                const promises = []
                data.forEach(item => {
                    const promise = getFile(item).then(data => { // 下载文件, 并存成ArrayBuffer对象
                        const arr_name = item.split("/")
                        const file_name = arr_name[arr_name.length - 1] // 获取文件名
                        zip.file(file_name, data, { binary: true }) // 逐个添加文件
                        cache[file_name] = data
                    })
                    promises.push(promise)
                })
                Promise.all(promises).then(() => {
                    zip.generateAsync({type:"blob"}).then(content => { // 生成二进制流
                        FileSaver.saveAs(content, +this.stuname+"见习文档.zip") // 利用file-saver保存文件
                    })
                })
            },
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        done();
                    })
                    .catch(_ => {});
            },
            onEditorBlur(){//失去焦点事件
            },
            onEditorFocus(){//获得焦点事件
            },
            onEditorChange(){//内容改变事件
            }
        },
        created() {
            this.username=this.$store.getters.getsName
            this.getInfo()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import '../../../common/css/font.css';
  .display{
    padding-top: 10px;
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
