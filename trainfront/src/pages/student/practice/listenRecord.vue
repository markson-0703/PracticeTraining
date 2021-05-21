<template>
  <el-tabs type="border-card">
    <el-tab-pane>
      <span slot="label"><i class="el-icon-edit"></i> 模板下载</span>
      <table id="listenData">
        <tr>
          <th>序号</th>
          <th>模板文件</th>
          <th>下载</th>
        </tr>
        <tr v-for="(item,index) in temData">
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
      <span slot="label"><i class="el-icon-upload2"></i>听课记录上传</span>
      <el-upload  class="upload-demo" ref="uploadForm" action="/yii/template/mould/uploadstudata"
                  :on-preview="handlePreview" :on-remove="handleRemove" :file-list="fileList" :auto-upload="false"
                  :on-change="handleChanged" :before-remove="beforeRemove" :on-success="uploadSuccess" :on-error="uploadError" :data="uploadData"
                  :before-upload="beforeUpload">
        <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
        <el-button style="margin-left: 10px;" size="small" type="success" @click="upload">上传</el-button>
        <div slot="tip" class="el-upload__tip">仅支持word文档的上传</div>
      </el-upload>
      <table id="recordStastics">
        <tr>
          <th style="width:5%">Id</th>
          <th style="width:35%">文件名</th>
          <th style="width:15%">提交时间</th>
          <th style="width:20%">操作</th>
        </tr>
        <tr v-for="(item,index) in myFiles">
          <td>{{index+1}}</td>
          <td>{{item.filename}}</td>
          <td>{{item.submitTime}}</td>
          <td>
          <span style="cursor: pointer">
          <el-button type="primary" icon="el-icon-search" circle @click="showFile(item.rId)"></el-button>
          </span>
          </td>
        </tr>
      </table>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
    export default {
        name: "listenRecord",
        data(){
            return{
                temData:[],
                fileList:[],
                uploadData:{},
                myFiles:[],
                kind:'',//类型
                username:''
            }
        },
        methods:{
            getTemdata(){
                let that = this
                that.$http.post('/yii/template/mould/listentemplate',{
                    type:2,
                    status:1,
                    kind:1
                }).then(function(res){
                    console.log(res.data)
                    that.temData=res.data.data
                })
            },
            downTem(kind){
                //模板下载
                let that = this
                that.$http.post('/yii/template/mould/downtemplate',{
                    type:2,
                    status:1,
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
                    kind:1,
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
                    this.getdatas()
                    console.log(res.data)
                }else{
                    this.$message({
                        message: res.message,
                        type: 'warning'
                    });
                }
            },
            getdatas(){
                //获取听课记录数据
                let that = this
                that.$http.post('/yii/template/mould/getmylistens',{
                    username:that.username,
                    type:2,
                    kind:1,
                    status:1//代表类型是实习普通文件
                }).then(function(res){
                    that.myFiles=res.data.data
                })
            },
            showFile(id){
                //查看当前听课记录
                let that = this
                that.$http.post('/yii/template/mould/getonelisten',{
                    rId:id
                }).then(function(res){
                    var originUrl='http://127.0.0.1/'+res.data.data
                    window.open("http://127.0.0.1:8012/onlinePreview?url="+encodeURIComponent(originUrl),"_blank")
                })
            }
        },
        created() {
            this.username=this.$store.getters.getsName
            this.getTemdata()
            this.getdatas()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import '../../../common/css/templateTable.css';
</style>
