<template>
    <div class="display">
      <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="见习文档" name="first">
          <table id="theirResources">
            <tr>
              <th>序号</th>
              <th>学号</th>
              <th>学生姓名</th>
              <th>文档数据</th>
              <th>详情</th>
            </tr>
            <tr v-for="(item,index) in basicInfo ">
              <td>{{index+1}}</td>
              <td>{{item.sno}}</td>
              <td>{{item.sName}}</td>
              <td>{{item.file}}</td>
              <td>
                <span><el-button type="primary" icon="el-icon-view" circle @click="filedetail(item.username)"></el-button></span>
              </td>
            </tr>
          </table>
<!--          显示文档详情-->
          <el-dialog title="文档详情" :visible.sync="dialogVisible" :before-close="handleClose" width="800px">
            <span><el-button type="danger" plain @click="fullDownload">批量下载</el-button></span>
            <table>
              <tr>
                <th style="width:5%">Id号</th>
                <th style="width:30%">类型</th>
                <th style="width:23%">文件名称</th>
                <th style="width:27%">上传时间</th>
                <th style="width:15%">操作</th>
              </tr>
              <tr v-for="item in fileData">
                <td>{{item.fId}}</td>
                <td v-if="item.status==1">普通文档</td>
                <td v-if="item.status==2">见习总结</td>
                <td>{{item.filename}}</td>
                <td>{{item.submitTime}}</td>
                <td>
             <span style="display:flex">
                <button style="border: 0px;float:left;margin-left:10px;cursor: pointer" @click="theirFile(item.fId)">查看</button>
                <button style="border: 0px;float:left;margin-left:10px;cursor: pointer" @click="downFile(item.fId)">下载</button>
              </span>
                </td>
              </tr>
            </table>
            <span slot="footer" class="dialog-footer">
           <el-button @click="dialogVisible= false">取 消</el-button>
           <el-button type="primary" @click="dialogVisible= false">确 定</el-button>
           </span>
          </el-dialog>
        </el-tab-pane>
        <el-tab-pane label="见习视频" name="second">
          <table id="theirVideos">
            <tr>
              <th>序号</th>
              <th>学号</th>
              <th>学生姓名</th>
              <th>视频数据</th>
              <th>详情</th>
            </tr>
            <tr v-for="(item,index) in basicInfo">
              <td>{{index+1}}</td>
              <td>{{item.sno}}</td>
              <td>{{item.sName}}</td>
              <td>{{item.video}}</td>
              <td>
                <span><el-button type="primary" icon="el-icon-view" circle @click="videoDetail(item.username)"></el-button></span>
              </td>
            </tr>
          </table>
          <!--          显示视频详情-->
          <el-dialog title="文档详情" :visible.sync="dialogVisible1" :before-close="handleClose" width="800px">
            <span><el-button type="danger" plain @click="fullDownload1">批量下载</el-button></span>
            <table>
              <tr>
                <th style="width:5%">Id号</th>
                <th style="width:23%">视频名称</th>
                <th style="width:27%">上传时间</th>
                <th style="width:45%">操作</th>
              </tr>
              <tr v-for="item in videoData">
                <td>{{item.vId}}</td>
                <td>{{item.videoname}}</td>
                <td>{{item.submitTime}}</td>
                <td>
              <span style="display:flex">
                <button style="border: 0px;margin-left:40px;cursor: pointer" @click="thisVideo(item.vId)">查看与下载</button>
              </span>
                </td>
              </tr>
            </table>
            <span slot="footer" class="dialog-footer">
            <el-button @click="dialogVisible1= false">取 消</el-button>
            <el-button type="primary" @click="dialogVisible1= false">确 定</el-button>
            </span>
          </el-dialog>
        </el-tab-pane>
      </el-tabs>
    </div>
</template>

<script>
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
        render(h) {
            return (<a on-click={ () => this.fullDownload() } href="javascript:;">批量下载</a>)
        },
        name: "resource",
        data(){
            return{
                activeName:'first',
                dialogVisible:false,
                dialogVisible1:false,
                basicInfo:[],//基本信息
                uname:'',//当前学生用户的用户名
                currentUname:'',
                fileData:[],//文档信息
                videoData:[],//视频信息
                username:'',//教师的用户名
                fileUrl:[],//生成zip文件夹
                videoUrl:[]
            }
        },
        methods:{
            handleClick(tab, event) {
                console.log(tab, event);
            },
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        done();
                    })
                    .catch(_ => {});
            },
            getBasicinfo(){
                //获取自己所指导学生的基础信息
                let that = this
                that.$http.post('/yii/resource/documention/basicinfo',{
                    username:that.username
                }).then(function(res){
                    console.log(res.data)
                    that.basicInfo=res.data.data
                })
            },
            filedetail(uname){
                //文档详情
                let that = this
                this.uname=uname
                that.$http.post('/yii/resource/documention/fileinfo',{
                    username:uname
                }).then(function(res){
                    console.log(res.data)
                    that.dialogVisible=true
                    that.fileData=res.data.data[0]
                    for(var i =0;i<res.data.data[1].length;i++){
                        that.fileUrl[i]='/zip'+res.data.data[1][i].url
                    }
                    console.log(that.fileUrl)
                })
            },
            videoDetail(uname){
                //视频详情
                let that = this
                this.currentUname=uname
                that.$http.post('/yii/resource/documention/videoinfo',{
                    username:uname
                }).then(function(res){
                    console.log(res.data)
                    that.dialogVisible1=true
                    that.videoData=res.data.data[0]
                    for(var i =0;i<res.data.data[1].length;i++){
                        that.videoUrl[i]='/zip'+res.data.data[1][i].url
                    }
                    console.log(that.videoUrl)
                })
            },
            theirFile(id){
                //查看文档
                let that = this
                that.$http.post('/yii/resource/documention/theirurl',{
                    id:id
                }).then(function(res){
                    console.log(res.data)
                    var originUrl='http://127.0.0.1/'+res.data.data//要预览文件的访问地址
                    window.open("http://127.0.0.1:8012/onlinePreview?url="+encodeURIComponent(originUrl),"_blank")
                })
            },
            thisVideo(id){
                //预览下载视频
                let that = this
                that.$http.post('/yii/resource/documention/thisurl',{
                    id:id
                }).then(function(res){
                    console.log(res.data)
                    var originUrl='http://127.0.0.1/'+res.data.data
                    window.open(originUrl,"_blank")
                })
            },
            downFile(id){
                //直接下载
                let that = this
                that.$http.post('/yii/resource/documention/theirurl',{
                    id:id
                }).then(function(res){
                    console.log(res.data)
                    var url='127.0.0.1/'+res.data.data
                    window.open('http://'+url,'_blank')
                })
            },
            fullDownload(){
                //批量下载并生成zip
                const data=this.fileUrl// 需要下载打包的路径, 可以是本地相对路径, 也可以是跨域的全路径
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
                        FileSaver.saveAs(content, this.uname+"见习文档.zip") // 利用file-saver保存文件
                    })
                })
            },
            fullDownload1(){
                //视频的批量下载并生成zip包
                const data=this.videoUrl// 需要下载打包的路径, 可以是本地相对路径, 也可以是跨域的全路径
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
                        FileSaver.saveAs(content, this.currentUname+"见习视频.zip") // 利用file-saver保存文件
                    })
                })

            }
        },
        created() {
            this.username=this.$store.getters.getsName
            this.getBasicinfo()
        }
    }
</script>

<style scoped="scoped" type="text/css">
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
