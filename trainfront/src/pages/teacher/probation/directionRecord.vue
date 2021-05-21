<template>
  <el-tabs type="border-card">
    <el-tab-pane>
      <span slot="label"><i class="el-icon-edit"></i> 模板下载</span>
      <table id="templateData">
        <tr>
          <th>序号</th>
          <th>模板文件</th>
          <th>下载</th>
        </tr>
        <tr v-for="(item,index) in templateData">
          <td>{{index+1}}</td>
          <td>{{item.filename}}</td>
          <td>
            <span style="cursor: pointer" @click="downloadTem(item.kind)">
              <el-button type="primary" icon="el-icon-download" circle></el-button>
            </span>
          </td>
        </tr>
      </table>
    </el-tab-pane>
    <el-tab-pane>
      <span slot="label"><i class="el-icon-paperclip"></i>指导记录上传</span>
        <el-upload  class="upload-demo" ref="uploadForm" action="/yii/template/mould/uploadtearecord"
                    :on-preview="handlePreview" :on-remove="handleRemove" :file-list="fileList" :auto-upload="false"
                    :on-change="handleChanged" :before-remove="beforeRemove" :on-success="uploadSuccess" :on-error="uploadError" :data="uploadData"
                    :before-upload="beforeUpload">
          <el-dropdown @command="handleCommand">
            <span class="el-dropdown-link">
            模板类型<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item command="1">课堂教学观摩记录</el-dropdown-item>
              <el-dropdown-item command="2">见习生观摩讨论指导记录</el-dropdown-item>
              <el-dropdown-item command="3">见习班级管理指导记录</el-dropdown-item>
              <el-dropdown-item command="4">见习教研活动指导记录</el-dropdown-item>
              <el-dropdown-item command="5">见习生试讲指导记录</el-dropdown-item>
              <el-dropdown-item command="6">指导教育见习工作总结</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
          <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
          <el-button style="margin-left: 10px;" size="small" type="success" @click="upload">上传</el-button>
          <div slot="tip" class="el-upload__tip">仅支持word文档的上传</div>
        </el-upload>
      <table id="recordStastics">
        <tr>
          <th style="width:5%">Id</th>
          <th style="width:15%">文件类型</th>
          <th style="width:35%">文件名</th>
          <th style="width:15%">提交时间</th>
          <th style="width:20%">操作</th>
        </tr>
        <tr v-for="item in myRecords">
          <td>{{item.rId}}</td>
          <td v-if="item.kind==1">课堂教学观摩记录</td>
          <td v-if="item.kind==2">见习生观摩讨论指导记录</td>
          <td v-if="item.kind==3">见习生班级管理指导记录</td>
          <td v-if="item.kind==4">见习教研活动指导记录</td>
          <td v-if="item.kind==5">见习生试讲指导记录</td>
          <td v-if="item.kind==6">指导教育见习工作总结</td>
          <td>{{item.filename}}</td>
          <td>{{item.submitTime}}</td>
          <td>
          <span style="cursor: pointer">
          <el-button type="primary" icon="el-icon-search" circle @click="showRecord(item.rId)"></el-button>
          </span>
          </td>
        </tr>
      </table>
      </el-tab-pane>
  </el-tabs>
</template>

<script>
    export default {
        name: "directionRecord",
        data(){
            return{
                username:'',
                templateData:[],
                fileList:[],
                myRecords:[],
                uploadData:{
                },
                kind:''//上传记录的类型
            }
        },
        methods:{
            handleCommand(command) {
                console.log(command)
                this.kind=parseInt(command)
            },
            showRecord(id){
                //查看当前记录
                let that = this
                that.$http.post('/yii/template/mould/getonerecord',{
                    rId:id
                }).then((res)=>{
                    console.log(res.data.data)
                    var originUrl='http://127.0.0.1/'+res.data.data
                    window.open("http://127.0.0.1:8012/onlinePreview?url="+encodeURIComponent(originUrl),"_blank")
                })
            },
            gettemData(){
                //模板数据
                let that = this
                that.$http.post('/yii/template/mould/getteatemplate',{
                    type:1,
                    status:2
                }).then(function (res) {
                    console.log(res.data)
                    that.templateData=res.data.data
                })
            },
            getRecords(){
                //获取自己提交的记录文件
                let that = this
                that.$http.post('/yii/template/mould/getmyrecords',{
                    username:that.username,
                    type:1
                }).then(function(res){
                    console.log(res.data)
                    that.myRecords=res.data.data
                })
            },
            downloadTem(kind){
                //模板下载
                let that = this
                that.$http.post('/yii/template/mould/downtemplate',{
                    type:1,
                    status:2,
                    kind:kind
                }).then(function(res){
                    console.log(res.data)
                    var originUrl='http://127.0.0.1/'+res.data.data//要预览文件的访问地址
                    window.open(originUrl)
                })
            },
            upload(){
                if(this.kind==0){
                    this.$message.error('请务必选择文件类型之后再上传!');
                }else{
                    this.$refs.uploadForm.submit()
                }
            },
            handlePreview(file){
                //点击文件列表中已上传的文件时的钩子
                console.log(file);//打印出了文件信息
            },
            beforeRemove(file) {
                return this.$confirm(`确定移除 ${ file.name }？`);
            },
            handleRemove(file){
                //文件列表移除文件时的钩子
                console.log(file);
            },
            handleChanged(file){
                //文件状态改变时的钩子，添加文件、上传成功和上传失败时都会被调用
                console.log(file)
            },
            uploadSuccess(res){
                if(res.code==200){
                    this.$message({
                        message: '上传成功!',
                        type: 'success'
                    });
                    this.getRecords()
                    console.log(res.data)
                }else{
                    this.$message({
                        message: res.message,
                        type: 'warning'
                    });
                }
            },
            uploadError(){
                this.$refs.uploadForm.clearFiles();
                this.$message.error('上传失败，请重新上传!');
            },
            beforeUpload(){
                this.uploadData = {
                    username:this.$store.getters.getsName,
                    kind:this.kind,
                    type:1
                }
                console.log(this.uploadData)
                let promise = new Promise((resolve) => {
                    this.$nextTick(function () {
                        resolve(true);
                    });
                });
                return promise;
            },
        },
        created() {
            this.username=this.$store.getters.getsName
            this.gettemData()
            this.getRecords()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import '../../../common/css/templateTable.css';
</style>
