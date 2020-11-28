<template>
  <div class="display">
    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
      <el-tab-pane label="文档上传" name="first">
        <el-upload  class="upload-demo" ref="uploadForm" action="/yii/record/record/uploadfiles"
                    :on-preview="handlePreview" :on-remove="handleRemove" :file-list="fileList" :auto-upload="false"
                    :on-change="handleChanged" :before-remove="beforeRemove" :on-success="uploadSuccess" :on-error="uploadError" :data="uploadData"
                    :before-upload="beforeUpload">
          <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
          <el-button style="margin-left: 10px;" size="small" type="success" @click="upload">上传</el-button>
          <div slot="tip" class="el-upload__tip">支持word文档及PPT文档的上传</div>
        </el-upload>
      </el-tab-pane>
       <table id="fileStastics" v-if="this.activeName=='first'">
         <tr >
           <th style="width:5%">Id</th>
           <th style="width:15%">文件名</th>
           <th style="width:35%">路径</th>
           <th style="width:15%">提交时间</th>
           <th style="width:20%">操作</th>
         </tr>
         <tr v-for="(item,index) in fileData">
           <td>{{item.fId}}</td>
           <td>{{item.filename}}</td>
           <td>{{item.path}}</td>
           <td>{{item.submitTime}}</td>
           <td>
             <span style="cursor: pointer" @click="preview(item.fId)"><el-button type="primary" size="small" style="background-color: #307d50">预览</el-button></span>
             <span style="cursor: pointer" @click="downFile(item.fId)"><el-button type="primary" size="small" style="background-color:firebrick">下载</el-button></span>
             <span style="cursor: pointer" @click="deleteFile(item.fId)"><el-button type="primary" size="small" style="background-color:orangered">删除</el-button></span>
           </td>
         </tr>
       </table>

      <el-tab-pane label="视频上传" name="second">
        <el-upload class="avatar-uploader" ref="videoForm" action="/yii/record/record/uploadvideo" accept=".mp4"
                   :on-success="handleVideoSuccess" :before-upload="beforeUploadVideo"
                   :on-progress="uploadVideoProcess" :data="myData">
          <video v-if="this.showVideoPath!=''&&!videoFlag"
                 :src="this.showVideoPath" class="video-avatar"
                 controls="controls">您的浏览器不支持视频播放</video>
          <i v-else-if="this.showVideoPath!=''&&!videoFlag"
             class="el-icon-plus avatar-uploader-icon"></i>
          <el-progress v-if="videoFlag == true"
                       type="circle"
                       :percentage="videoUploadPercent"
                       style="margin-top:30px;"></el-progress>
          <el-button class="video-btn"
                     slot="trigger"
                     size="small"
                     v-if="isShowUploadVideo"
                     type="primary">选取文件</el-button>
<!--          <el-button style="margin-left: 10px;" size="small" type="success" @click="videoUpload">上传</el-button>-->
          <P v-if="isShowUploadVideo"
             class="text">请保证视频格式正确，且不超过200M</P>

        </el-upload>
      </el-tab-pane>
      <table id="videoStastics" v-if="this.activeName=='second'">
        <tr >
          <th style="width:5%">Id</th>
          <th style="width:15%">视频名称</th>
          <th style="width:35%">路径</th>
          <th style="width:15%">提交时间</th>
          <th style="width:20%">操作</th>
        </tr>
        <tr v-for="item in videoData">
          <td>{{item.vId}}</td>
          <td>{{item.videoname}}</td>
          <td>{{item.path}}</td>
          <td>{{item.submitTime}}</td>
          <td>
            <span style="cursor: pointer" @click="preVideo(item.vId)"><el-button type="primary" size="small" style="background-color: #307d50">预览</el-button></span>
            <span style="cursor: pointer" @click="downVideo(item.vId)"><el-button type="primary" size="small" style="background-color:firebrick">下载</el-button></span>
            <span style="cursor: pointer" @click="deleteVideo(item.vId)"><el-button type="primary" size="small" style="background-color:orangered">删除</el-button></span>
          </td>
        </tr>
      </table>
    </el-tabs>
  </div>
</template>

<script>
    export default {
        name: "resourceManage",
        data(){
            return{
                activeName: 'first',
                username:'',
                uploadData:{
                },
                myData:{

                },
                fileList:[],
                fileData:[],
                videoData:[],
                showVideoPath:'',//从后台返回的视频url
                videoFlag:false , //是否显示进度条
                videoUploadPercent:"", //进度条的进度
                isShowUploadVideo:true //显示按钮
            }
        },
        methods:{
            getfileData(){
                //获得文件数据
                let that = this
                that.$http.post('/yii/probation/infomation/getfile',{
                    username:that.username,
                    type:1
                }).then(function(res){
                    console.log(res.data)
                    that.fileData=res.data.data
                })
            },
            getvideoData(){
                //获取视频数据
                let that = this
                that.$http.post('/yii/probation/infomation/getvideo',{
                    username:that.username,
                    type:1
                }).then(function(res){
                    console.log(res.data)
                    that.videoData=res.data.data
                })
            },
            handleClick(tab, event) {
                console.log(tab, event);
            },
            handlePreview(file){
                //点击文件列表中已上传的文件时的钩子
                console.log(file);//打印出了文件信息
            },
            beforeRemove(file, fileList) {
                return this.$confirm(`确定移除 ${ file.name }？`);
            },
            handleRemove(file){
                //文件列表移除文件时的钩子
                console.log(file);
            },
            handleChanged(file,fileList){
                //文件状态改变时的钩子，添加文件、上传成功和上传失败时都会被调用
                console.log(file)
                // this.fileList.push(file)
            },
            beforeUpload(file){
                this.uploadData = {username:this.$store.getters.getsName}
                console.log(this.uploadData)
                let promise = new Promise((resolve) => {
                    this.$nextTick(function () {
                        resolve(true);
                    });
                });
                return promise;
            },
            upload(){
                //上传
                this.$refs.uploadForm.submit()
                },
            uploadSuccess(res){
                if(res.code==200){
                    this.$message({
                        message: '恭喜你，上传成功',
                        type: 'success'
                    });
                    this.getfileData()
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
            preview(id){
                //预览
                let that = this
                that.$http.post('/yii/probation/infomation/fileurl',{
                    id:id
                }).then(function (res) {
                    console.log(res.data)
                    var originUrl='http://127.0.0.1/'+res.data.data//要预览文件的访问地址
                    window.open("http://127.0.0.1:8012/onlinePreview?url="+encodeURIComponent(originUrl),"_blank")
                })
            },
            preVideo(id){
                //预览视频
                let that = this
                that.$http.post('/yii/probation/infomation/videourl',{
                    id:id
                }).then(function (res) {
                    console.log(res.data)
                    var originUrl='http://127.0.0.1/'+res.data.data//要预览文件的访问地址
                    window.open("http://127.0.0.1:8012/onlinePreview?url="+encodeURIComponent(originUrl),"_blank")
                })

            },
            downFile(id){
                //下载
                let that = this
                that.$http.post('/yii/probation/infomation/fileurl',{
                    id:id
                }).then(function (res) {
                    console.log(res.data)
                    var originUrl='http://127.0.0.1/'+res.data.data//要预览文件的访问地址
                    window.open(originUrl)
                })

            },
            downVideo(id){
                //下载视频
                let that = this
                that.$http.post('/yii/probation/infomation/videourl',{
                    id:id
                }).then(function (res) {
                    console.log(res.data)
                    var videoUrl='http://127.0.0.1/'+res.data.data//要预览文件的访问地址
                    window.open(videoUrl)
                })
            },
            deleteFile(id){
                //删除
                this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    that.$http.post('/yii/probation/infomation/delmyfile',{
                        id:id
                    }).then(function(res){
                        console.log(res.data)
                        if(res.data.message=="success"){
                            that.getfileData()
                            alert('删除成功!')
                        }else{
                            alert('操作失败!')
                        }
                    })
                }).catch((err)=>{
                    console.log(err)
                })
            },
            deleteVideo(id){
                //删除视频
                this.$confirm('此操作将永久删除该视频, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    that.$http.post('/yii/probation/infomation/delmyvideo',{
                        id:id
                    }).then(function(res){
                        console.log(res.data)
                        if(res.data.message=="success"){
                            that.getvideoData()
                            alert('删除成功!')
                        }else{
                            alert('操作失败!')
                        }
                    })
                }).catch((err)=>{
                    console.log(err)
                })
            },
            handleVideoSuccess(res,file){
                //上传成功回调
                console.log(file)
                this.isShowUploadVideo=true;
                this.videoFlag=false;
                this.videoUploadPercent=0;
                console.log(res)
                if(res.code==200){
                    this.$message({
                        message: '恭喜你，上传成功',
                        type: 'success'
                    });
                    this.getvideoData()
                    var url='http://127.0.0.1/'+res.data.data
                    var videoUrl="http://127.0.0.1:8012/onlinePreview?url="+encodeURIComponent(url)
                    this.showVideoPath=videoUrl
                }else{
                    this.$message.error('视频上传失败，请重新上传！');
                }


            },
            beforeUploadVideo(file){
                //上传前回调
                console.log(file.size)
                const isLimit = parseInt(file.size / 1024 / 1024);
                console.log(isLimit)
                if (['video/mp4'].indexOf(file.type) == -1) {
                    this.$message.error('请上传正确的视频格式!');
                    return false;
                }
                if (isLimit>200) {
                    this.$message.error('上传视频大小不能超过200MB哦!');
                    return false;
                }
                this.isShowUploadVideo=false
                this.myData = {username:this.$store.getters.getsName}
                console.log(this.myData)
                let promise = new Promise((resolve) => {
                    this.$nextTick(function () {
                        resolve(true);
                    });
                });
                return promise;
            },
            uploadVideoProcess(event,file,fileList){
                //进度条回调
                this.videoFlag=true;
                this.videoUploadPercent = file.percentage.toFixed(0) * 1;
            },
            videoUpload(){
                this.$refs.videoForm.submit()
            }
        },
        created() {
            this.username=this.$store.getters.getsName
            this.getfileData()
            this.getvideoData()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import "../../../common/css/videoShow.css";
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
