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
        <tr v-for="(item,index) in planData">
          <td>{{index+1}}</td>
          <td>{{item.filename}}</td>
          <td>
            <span style="cursor: pointer" @click="downTem(item.kind)">
              <el-button type="primary" icon="el-icon-download" circle></el-button>
            </span>
          </td>
        </tr>
      </table>
    </el-tab-pane>
    <el-tab-pane>
      <span slot="label"><i class="el-icon-paperclip"></i>工作计划上传</span>
      <el-upload  class="upload-demo" ref="uploadForm" action="/yii/template/mould/uploadteaplan"
                  :on-preview="handlePreview" :on-remove="handleRemove" :file-list="fileList" :auto-upload="false"
                  :on-change="handleChanged" :before-remove="beforeRemove" :on-success="uploadSuccess" :on-error="uploadError" :data="uploadData"
                  :before-upload="beforeUpload">
        <el-dropdown @command="handleCommand">
            <span class="el-dropdown-link">
            模板类型<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item command="1">实习动员大会会议记录</el-dropdown-item>
            <el-dropdown-item command="2">实习工作计划</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
        <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
        <el-button style="margin-left: 10px;" size="small" type="success" @click="upload">上传</el-button>
        <div slot="tip" class="el-upload__tip">仅支持word文档的上传</div>
      </el-upload>
      <table id="planStastics">
        <tr>
          <th style="width:5%">Id</th>
          <th style="width:15%">文件类型</th>
          <th style="width:35%">文件名</th>
          <th style="width:15%">提交时间</th>
          <th style="width:20%">操作</th>
        </tr>
        <tr v-for="(item,index) in myPlans">
          <td>{{index+1}}</td>
          <td v-if="item.kind==1">实习动员大会会议记录</td>
          <td v-if="item.kind==2">实习工作计划</td>
          <td>{{item.filename}}</td>
          <td>{{item.submitTime}}</td>
          <td>
          <span style="cursor: pointer">
          <el-button type="primary" icon="el-icon-search" circle @click="showPlan(item.rId)"></el-button>
          </span>
          </td>
        </tr>
      </table>
    </el-tab-pane>
    <el-tab-pane>
      <span slot="label"><i class="el-icon-mouse"></i>实习指导记录上传</span>
      <el-upload  class="upload-demo" ref="uploadForm1" action="/yii/template/mould/uploadteaplan"
                  :on-preview="handlePreview1" :on-remove="handleRemove1" :file-list="fileList1" :auto-upload="false"
                  :on-change="handleChanged1" :before-remove="beforeRemove1" :on-success="uploadSuccess1" :on-error="uploadError1" :data="uploadData1"
                  :before-upload="beforeUpload1">
        <el-dropdown @command="handleCommand">
            <span class="el-dropdown-link">
            模板类型<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item command="1">实习生试讲实录</el-dropdown-item>
            <el-dropdown-item command="2">实习生授课实录</el-dropdown-item>
            <el-dropdown-item command="3">实习生公开课试讲实录</el-dropdown-item>
            <el-dropdown-item command="4">实习生公开课实录</el-dropdown-item>
            <el-dropdown-item command="5">班主任工作指导记录表</el-dropdown-item>
            <el-dropdown-item command="6">教育研究指导记录表</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
        <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
        <el-button style="margin-left: 10px;" size="small" type="success" @click="upload1">上传</el-button>
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
        <tr v-for="(item,index) in myRecords">
          <td>{{index+1}}</td>
          <td v-if="item.kind==1">实习生试讲实录</td>
          <td v-if="item.kind==2">实习生授课实录</td>
          <td v-if="item.kind==3">实习生公开课试讲实录</td>
          <td v-if="item.kind==4">实习生公开课实录</td>
          <td v-if="item.kind==5">班主任工作指导记录表</td>
          <td v-if="item.kind==6">教育研究指导记录表</td>
          <td>{{item.filename}}</td>
          <td>{{item.submitTime}}</td>
          <td>
          <span style="cursor: pointer">
          <el-button type="primary" icon="el-icon-search" circle @click="showPlan(item.rId)"></el-button>
          </span>
          </td>
        </tr>
      </table>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
    export default {
        name: "practicePlan",
        data(){
            return{
                planData:[],
                username:'',
                fileList:[],
                fileList1:[],
                myPlans:[],
                myRecords:[],
                uploadData:{},
                uploadData1:{},
                kind:''//上传计划的类型
            }
        },
        methods:{
            handleCommand(command) {
                console.log(command)
                this.kind=parseInt(command)
            },
           temData(){
                //模板数据
                let that = this
                that.$http.post('/yii/template/mould/getteatemplate',{
                    type:2,
                    status:2
                }).then(function (res) {
                    console.log(res.data)
                    that.planData=res.data.data
                })
            },
            downTem(kind){
                //模板下载
                let that = this
                that.$http.post('/yii/template/mould/downtemplate',{
                    type:2,
                    status:2,
                    kind:kind
                }).then(function(res){
                    console.log(res.data)
                    var originUrl='http://127.0.0.1/'+res.data.data//要预览文件的访问地址
                    window.open(originUrl)
                })
            },
            handlePreview(file){
                console.log(file);
            },
            handlePreview1(file){
                console.log(file);
            },
            handleRemove(file){
                console.log(file);
            },
            handleRemove1(file){
                console.log(file);
            },
            handleChanged(file){
                console.log(file)
            },
            handleChanged1(file){
                console.log(file)
            },
            beforeRemove(file) {
                return this.$confirm(`确定移除 ${ file.name }？`);
            },
            beforeRemove1(file) {
                return this.$confirm(`确定移除 ${ file.name }？`);
            },
            uploadSuccess(res){
                if(res.code==200){
                    this.$message({
                        message: '上传成功!',
                        type: 'success'
                    });
                    this.getPlans()
                    console.log(res.data)
                }else{
                    this.$message({
                        message: res.message,
                        type: 'warning'
                    });
                }
            },
            uploadSuccess1(res){
                if(res.code==200){
                    this.$message({
                        message: '上传成功!',
                        type: 'success'
                    });
                    this.getRecords()
                }else{
                    this.$message({
                        message: res.message,
                        type: 'warning'
                    });
                }
            },
            upload(){
                if(this.kind==0){
                    this.$message.error('请务必选择文件类型之后再上传!');
                }else{
                    this.$refs.uploadForm.submit()
                }
            },
            upload1(){
                if(this.kind==0){
                    this.$message.error('请务必选择文件类型之后再上传!');
                }else{
                    this.$refs.uploadForm1.submit()
                }
            },
            beforeUpload(){
                this.uploadData = {
                    username:this.$store.getters.getsName,
                    kind:this.kind,
                    type:2,
                    status:1//状态1代表上传的是计划文件
                }
                console.log(this.uploadData)
                let promise = new Promise((resolve) => {
                    this.$nextTick(function () {
                        resolve(true);
                    });
                });
                return promise;
            },
            beforeUpload1(){
                this.uploadData1 = {
                    username:this.$store.getters.getsName,
                    kind:this.kind,
                    type:2,
                    status:2//状态2代表上传的是指导记录
                }
                console.log(this.uploadData)
                let promise = new Promise((resolve) => {
                    this.$nextTick(function () {
                        resolve(true);
                    });
                });
                return promise;
            },
            uploadError(){
                this.$refs.uploadForm.clearFiles();
                this.$message.error('上传失败，请重新上传!');
            },
            uploadError1(){
                this.$refs.uploadForm1.clearFiles();
                this.$message.error('上传失败，请重新上传!');
            },
            getPlans(){
                //获取自己提交的计划文件
                let that = this
                that.$http.post('/yii/template/mould/getmyplans',{
                    username:that.username,
                    type:2,
                    status:1//代表类型是实习计划
                }).then(function(res){
                    console.log(res.data)
                    that.myPlans=res.data.data
                })
            },
            getRecords(){
                //获取自己提交的实习指导文件
                let that = this
                that.$http.post('/yii/template/mould/getmyplans',{
                    username:that.username,
                    type:2,
                    status:2//代表类型是实习记录文件
                }).then(function(res){
                    console.log(res.data)
                    that.myRecords=res.data.data
                })
            },
            showPlan(id){
                // 查看当前计划
                let that = this
                that.$http.post('/yii/template/mould/getonerecord',{
                    rId:id
                }).then(function(res){
                    var originUrl='http://127.0.0.1/'+res.data.data
                    window.open("http://127.0.0.1:8012/onlinePreview?url="+encodeURIComponent(originUrl),"_blank")
                })
            }
        },
        created() {
            this.username=this.$store.getters.getsName
            this.temData()
            this.getPlans()
            this.getRecords()
        }
    }
</script>

<style scoped="scoped" type="text/css">
@import '../../../common/css/templateTable.css';
</style>
