<template>
<div class="main">
  <div class="upperInfo">
    <span style="cursor: pointer;" @click="designTem">
      <el-button type="info">教学设计模板下载</el-button>
    </span>
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
  <div class="seeDetail" v-show="flag">
    <table id="designStastics">
      <tr>
        <th style="width:5%">序号</th>
        <th style="width:10%">文件名</th>
        <th style="width:10%">提交时间</th>
        <th style="width:5%">操作</th>
        <th style="width:70%">审阅意见</th>
      </tr>
      <tr v-for="(item,index) in designList">
        <td>{{index+1}}</td>
        <td>{{item.filename}}</td>
        <td>{{item.submitTime}}</td>
        <td>
          <span style="cursor: pointer">
          <el-button type="primary" icon="el-icon-search" circle @click="showData(item.rId)"></el-button>
          </span>
        </td>
        <td v-if="item.suggestion==null">教师暂未给予审阅意见!</td>
        <td v-if="item.suggestion!=null">{{item.suggestion}}</td>
      </tr>
    </table>
  </div>
</div>
</template>

<script>
    export default {
        name: "teachDesign",
        data(){
            return{
                username:'',
                fileList:[],
                designList:[],
                uploadData:{},
                flag:false
            }
        },
        methods:{
            checkShow(){
                //判断下部分是否显示
                if(this.designList!=null) {
                    if (this.designList.length > 0) {
                        this.flag = true
                    } else{
                        this.flag = false
                    }
                }
            },
            showData(id){
                // 查看当前教学设计
                let that = this
                that.$http.post('/yii/template/mould/getonelisten',{
                    rId:id
                }).then(function(res){
                    var originUrl='http://127.0.0.1/'+res.data.data
                    window.open("http://127.0.0.1:8012/onlinePreview?url="+encodeURIComponent(originUrl),"_blank")
                })
            },
            designData(){
                let that = this
                that.$http.post('/yii/practice/message/getdesign',{
                    username:this.username,
                    type:2,
                    kind:2,
                    status:1
                }).then(function(res){
                    console.log(res.data)
                    this.designList=res.data.data
                    this.checkShow()
                })
            },
            designTem(){
                let that = this
                that.$http.post('/yii/template/mould/downtemplate',{
                    type:2,
                    status:1,
                    kind:2
                }).then(function(res){
                    console.log(res.data)
                    var originUrl='http://127.0.0.1/'+res.data.data//要预览文件的访问地址
                    window.open(originUrl)
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
            uploadSuccess(res){
                if(res.code==200){
                    this.$message({
                        message: '上传成功!',
                        type: 'success'
                    });
                    this.designData()
                }else{
                    this.$message({
                        message: res.message,
                        type: 'warning'
                    });
                }
            },
            upload(){
                this.$refs.uploadForm.submit()
            },
            uploadError(){
                this.$refs.uploadForm.clearFiles();
                this.$message.error('上传失败，请重新上传!');
            },
            beforeUpload(){
                this.uploadData = {
                    username:this.$store.getters.getsName,
                    kind:2,
                    type:2,
                    status:1//状态1代表上传的是普通文件
                }
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
            this.designData()
            this.checkShow()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import '../../../common/css/tableLine.css';
.upperInfo span{
  display: inline-block;
  width:200px;
}
.upperInfo {
  text-align: center;
  padding-bottom: 20px;
}
</style>
