<template>
<div style="text-align: center">
  <div class="baseInfo">
  <p>教育研究是指人们在一定的教育理论指导下，遵循一定的研究程序，运用一定的方法来解决教育问题，以探索教育规律为目的的富有创造性的认识和实践活动</p>
      <p>教育研究的一般程序</p>
    <ul>
      <li>选题及论证阶段</li>
      <li>教育科研课题方案的设计</li>
      <li>教育研究报告的撰写</li>
    </ul>
  </div>
  <div class="doFile">
    <span style="cursor: pointer;" @click="downTem()">
       <el-button type="danger" plain >教育研究报告模板下载</el-button>
    </span>
    <el-upload  class="upload-demo" ref="uploadForm" action="/yii/template/mould/uploadstudata"
                :on-preview="handlePreview" :on-remove="handleRemove" :file-list="fileList" :auto-upload="false"
                :on-change="handleChanged" :before-remove="beforeRemove" :on-success="uploadSuccess" :on-error="uploadError" :data="uploadData"
                :before-upload="beforeUpload">
      <el-button slot="trigger" size="small" type="primary" style="margin-top:10px">选取文件</el-button>
      <el-button style="margin-left: 10px;" size="small" type="success" @click="upload">上传</el-button>
      <div slot="tip" class="el-upload__tip">仅支持word文档的上传</div>
    </el-upload>
    <p v-show="!flag" style="font-weight: bolder;font-size: 24px">暂未有任何提交!</p>
    <table id="reportStastics" v-show="flag">
      <tr>
        <th style="width:5%">Id</th>
        <th style="width:35%">文件名</th>
        <th style="width:15%">提交时间</th>
        <th style="width:20%">操作</th>
      </tr>
      <tr v-for="(item,index) in myReports">
        <td>{{index+1}}</td>
        <td>{{item.filename}}</td>
        <td>{{item.submitTime}}</td>
        <td>
          <span style="cursor: pointer">
          <el-button type="primary" icon="el-icon-search" circle @click="showReport(item.rId)"></el-button>
          </span>
        </td>
      </tr>
    </table>
  </div>
</div>
</template>

<script>
    export default {
        name: "researchReport",
        data() {
            return{
               username:'',
                fileList:[],
                flag:false,
                uploadData:{},
                myReports:[]
            }
        },
        methods:{
            check(){
                if(this.myReports.length>0){
                    this.flag=true
                }else{
                    this.flag=true
                }
            },
            downTem(){
                //下载教育研究报告的模板
                let that = this
                that.$http.post('/yii/template/mould/downtemplate',{
                    type:2,
                    status:1,//学生用模板
                    kind:9
                }).then(function(res){
                    console.log(res.data)
                    var originUrl='http://127.0.0.1/'+res.data.data//要预览文件的访问地址
                    window.open(originUrl)
                })
            },
            getReport(){
                //获取教育报告数据
                let that = this
                that.$http.post('/yii/template/mould/getmylistens',{
                    username:that.username,
                    type:2,
                    kind:9,
                    status:1//代表类型是实习普通文件
                }).then(function(res){
                    that.myReports=res.data.data
                    that.check()
                })
            },
            showReport(id){
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
            uploadError(){
                this.$refs.uploadForm.clearFiles();
                this.$message.error('上传失败，请重新上传!');
            },
            beforeUpload(){
                this.uploadData = {
                    username:this.$store.getters.getsName,
                    kind:9,
                    type:2,
                    status:1//状态1代表上传的普通实习文件
                }
                let promise = new Promise((resolve) => {
                    this.$nextTick(function () {
                        resolve(true);
                    });
                });
                return promise;
            },
            upload(){
                this.$refs.uploadForm.submit()
            },
            uploadSuccess(res){
                if(res.code==200){
                    this.$message({
                        message: '上传成功!',
                        type: 'success'
                    });
                    this.getReport()
                    console.log(res.data)
                }else{
                    this.$message({
                        message: res.message,
                        type: 'warning'
                    });
                }
            },
        },
        created() {
            this.username=this.$store.getters.getsName
            this.getReport()
            this.check()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import '../../../common/css/templateTable.css';
.baseInfo{
  text-align: center;
  width:50%;
  border-style:solid;
  border-width:3px;
  border-color:#05607c;
  height:200px;
  display: inline-block;
}
.baseInfo p{
    margin:10px;
  }
.doFile{
    padding-top: 15px;
  }
</style>
