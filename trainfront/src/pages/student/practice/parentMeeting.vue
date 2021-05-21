<template>
<div>
  <div class="upper">
    <span>
    <el-upload  class="upload-demo" ref="uploadForm" action="/yii/template/mould/uploadstudata"
                :on-preview="handlePreview" :on-remove="handleRemove" :file-list="fileList" :auto-upload="false"
                :on-change="handleChanged" :before-remove="beforeRemove" :on-success="uploadSuccess" :on-error="uploadError" :data="uploadData"
                :before-upload="beforeUpload">
      <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
      <el-button style="margin-left: 10px;" size="small" type="success" @click="upload">上传</el-button>
      <div slot="tip" class="el-upload__tip">仅支持word文档的上传</div>
    </el-upload>
    </span>
  </div>
  <div v-show="!flag">
    <p style="font-size: 28px;font-weight: bolder">暂未有任何提交！</p>
  </div>
  <div class="seeDetail" v-show="flag">
    <table>
      <tr>
        <th>序号</th>
        <th>文件名</th>
        <th>提交时间</th>
        <th>操作</th>
      </tr>
      <tr v-for="(item,index) in meetList">
        <td>{{index+1}}</td>
        <td>{{item.filename}}</td>
        <td>{{item.submitTime}}</td>
        <td>
          <span style="cursor: pointer">
          <el-button type="primary" icon="el-icon-search" circle @click="showMeet(item.rId)"></el-button>
          </span>
        </td>
      </tr>
    </table>
  </div>
</div>
</template>

<script>
    export default {
        name: "parentMeeting",
        data(){
            return{
                username:'',
                flag:false,
                meetList:[],
                fileList:[],
                uploadData:{}
            }
        },
        methods:{
            checkShow(){
                if(this.meetList!=null) {
                    if (this.meetList.length > 0) {
                        this.flag = true
                    } else{
                        this.flag = false
                    }
                }
            },
            parentData(){
                let that = this
                that.$http.post('/yii/practice/message/getdesign',{
                    username:this.username,
                    type:2,
                    kind:8,
                    status:1
                }).then(function(res){
                    console.log(res.data)
                    this.meetList=res.data.data
                    this.checkShow()
                })
            },
            showMeet(id){
                let that = this
                that.$http.post('/yii/template/mould/getonelisten',{
                    rId:id
                }).then(function(res){
                    var originUrl='http://127.0.0.1/'+res.data.data
                    window.open("http://127.0.0.1:8012/onlinePreview?url="+encodeURIComponent(originUrl),"_blank")
                })
            },
            handlePreview(file){
                console.log(file);
            },
            handleRemove(file){
                console.log(file);
            },
            handleChanged(file){
                console.log(file)
            },
            beforeRemove(file) {
                return this.$confirm(`确定移除 ${ file.name }？`);
            },
            upload(){
                this.$refs.uploadForm.submit()
            },
            uploadError(){
                this.$refs.uploadForm.clearFiles();
                this.$message.error('上传失败，请重新上传!');
            },
            uploadSuccess(res){
                if(res.code==200){
                    this.$message({
                        message: '上传成功!',
                        type: 'success'
                    });
                    this.parentData()
                }else{
                    this.$message({
                        message: res.message,
                        type: 'warning'
                    });
                }
            },
            beforeUpload(){
                this.uploadData = {
                    username:this.$store.getters.getsName,
                    kind:8,
                    type:2,
                    status:1
                }
                let promise = new Promise((resolve) => {
                    this.$nextTick(function () {
                        resolve(true);
                    });
                });
                return promise;
            }
        },
        created() {
            this.username=this.$store.getters.getsName
            this.parentData()
            this.checkShow()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import '../../../common/css/tableLine.css';
  .seeDetail{
    padding-top: 15px;
  }
</style>
