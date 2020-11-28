<template>
<div class="display">
  <div class="top-menu">
    <el-button type="primary" size="small" icon="el-icon-download" @click="conclusionTem">模板下载</el-button>
    <el-button size="small" @click="preview" style="background-color:tan;margin-left: 20px;float: right" type="primary" >预览</el-button>
    <el-upload class="file-upload" ref="conclusionForm" action="/yii/record/record/conclusion"
               :on-preview="handlePreview" :on-remove="handleRemove" :file-list="fileList" :auto-upload="false"
               :on-change="handleChanged" :before-remove="beforeRemove" :on-success="uploadSuccess" :on-error="uploadError"
               :data="uploadData" :before-upload="beforeUpload">
      <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
      <el-button style="margin-left: 10px;" size="small" type="success" @click="upload">文件上传</el-button>
      <div slot="tip" class="el-upload__tip">仅支持word文档的上传</div>
    </el-upload>
  </div>
</div>
</template>

<script>
    export default {
        name: "myConclusion",
        data(){
            return{
                username:'',
                flag:0,//判断是否提交了见习总结
                fileList:[],
                uploadData:{
                },
            }
        },
        methods:{
            checkFlag(){
                //判断是否提交总结
                let that = this
                that.$http.post('/yii/probation/infomation/getflag',{
                    username:that.username
                }).then(function (res) {
                    if(res.data.message=="success"){
                        that.flag=1
                    }
                })
            },
            conclusionTem(){
                //见习总结模板下载
                let that = this
                that.$http.post('/yii/record/record/mytemplate1',{
                    kind:7
                }).then(function (res) {
                    console.log(res.data)
                    var originUrl='http://127.0.0.1/'+res.data.data//要预览文件的访问地址
                    window.open(originUrl)
                })
            },
            preview(){
                //预览
                if(this.flag==0){
                    this.$message.error('你还未上传见习总结，暂时无法预览!');
                }else{
                    let that = this
                    that.$http.post('/yii/probation/infomation/getconclusion',{
                        username:that.username,
                        status:2
                    }).then(function(res){
                        console.log(res.data)
                        var originUrl='http://127.0.0.1/'+res.data.data//要预览文件的访问地址
                        window.open("http://127.0.0.1:8012/onlinePreview?url="+encodeURIComponent(originUrl),"_blank")
                    })
                }
            },
            handlePreview(file){
                //点击文件列表中已上传的文件时的钩子
                console.log(file);//打印出了文件信息
            },
            handleRemove(file){
                //文件列表移除文件时的钩子
                console.log(file);
            },
            handleChanged(file){
                //文件状态改变时的钩子，添加文件、上传成功和上传失败时都会被调用
                console.log(file)
            },
            beforeRemove(file) {
                return this.$confirm(`确定移除 ${ file.name }？`);
            },
            uploadError(){
                this.$refs.conclusionForm.clearFiles();
                this.$message.error('上传失败，请重新上传!');
            },
            uploadSuccess(res){
                if(res.code==200){
                    this.$message({
                        message: '恭喜你，见习总结上传成功',
                        type: 'success'
                    });
                    console.log(res.data)
                }else{
                    this.$message({
                        message: res.message,
                        type: 'warning'
                    });
                }
            },
            beforeUpload(){
                //在上传文件之前，先上传需要的变量
                this.uploadData = {username:this.$store.getters.getsName,}
                let promise = new Promise((resolve) => {
                    this.$nextTick(function () {
                        resolve(true);
                    });
                });
                return promise;
            },
            upload(){
                //见习总结文件上传
                if(this.flag==1){
                    this.$message({
                        message: '已提交见习总结，请勿重复提交！',
                        type: 'warning'
                    });
                }else{
                    this.$refs.conclusionForm.submit()
                }
            }
        },
        created() {
            this.username=this.$store.getters.getsName
            this.checkFlag()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  .display{
    text-align: center;
    height:100%;
  }
  .top-menu{
    text-align: center;
    margin-left: 500px;
    float:left;
    padding-bottom: 10px;
  }
  .file-upload{
    float:right;
    margin-left: 20px;
  }
</style>
