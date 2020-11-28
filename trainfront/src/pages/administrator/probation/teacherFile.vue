<template>
  <div class="display1">
    <div class="search">
      <div class="meeting">
        <el-input v-model="inputTea" placeholder="请输入教师姓名" size="mini"></el-input>
      </div>
      <button class="btn3" v-on:click="teaRecord()">搜索</button>
    </div>
    <table id="teafileStastics">
      <tr>
        <td>序号</td>
        <td>教师姓名</td>
        <td>工号</td>
        <td>文件数据</td>
        <td>详情</td>
      </tr>
      <tr v-for="(item,index) in teafileData">
        <td>{{index+1}}</td>
        <td>{{item.tName}}</td>
        <td>{{item.job_num}}</td>
        <td>{{item.number}}</td>
        <td>
          <span><el-button type="primary" icon="el-icon-view" circle @click="recordDetail(item.username,item.tName)"></el-button></span>
        </td>
      </tr>
    </table>
    <!--        实现分页效果-->
    <div class="page">
      <ul class="pagination pagination-sm"><!--分页-->
        <li class="page-item" v-if="currentpage!=1">
          <span class="page-link" v-on:click="prepage(currentpage)">上一页</span>
        </li>
        <li class="page-item" v-for="(index,key) in pagenums" :key="key" v-bind:class="{ active: currentpage == index} ">
          <span class="page-link" v-on:click="pageChange(index)">{{ index }}</span>
        </li>
        <li class="page-item" v-if="currentpage!=totalpage">
          <span class="page-link" v-on:click="nextpage(currentpage)">下一页</span>
        </li>
        <li class="page-item">
          <span class="page-link">共<i>{{totalpage}}</i>页</span>
        </li>
      </ul>
    </div>
<!--    显示各个老师详细记录数据-->
    <el-dialog title="记录详情" :visible.sync="dialogVisible" :before-close="handleClose" width="800px">
      <span><el-button type="danger" plain @click="bulkDownload">批量下载</el-button></span>
      <table>
        <tr>
          <th style="width:5%">Id号</th>
          <th style="width:20%">类型</th>
          <th style="width:33%">文件名称</th>
          <th style="width:27%">上传时间</th>
          <th style="width:15%">操作</th>
        </tr>
        <tr v-for="item in filesDetail">
          <td>{{item.rId}}</td>
          <td v-if="item.status==1">普通记录</td>
          <td v-if="item.status==2">见习总结</td>
          <td>{{item.filename}}</td>
          <td>{{item.submitTime}}</td>
          <td>
              <span style="display:flex">
                <button style="border: 0px;float:left;margin-left:10px;cursor: pointer" @click="havesee(item.rId)">查看</button>
                <button style="border: 0px;float:left;margin-left:10px;cursor: pointer" @click="downRecord(item.rId)">下载</button>
              </span>
          </td>
        </tr>
      </table>
      <span slot="footer" class="dialog-footer">
           <el-button @click="dialogVisible= false">取 消</el-button>
           <el-button type="primary" @click="dialogVisible= false">确 定</el-button>
           </span>
    </el-dialog>
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
        name: "teacherFile",
        data(){
            return{
                currentpage: 1, // 当前页
                visiblepage: 10, // 可见页数
                totalpage: '', // 总页数
                teafileData:[],//教师记录数据
                tName:'',//教师姓名
                inputTea:'',//教师姓名
                dialogVisible:false,
                filesDetail:[],
                recordUrl:[]
            }
        },
        methods:{
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        done();
                    })
                    .catch(_ => {});
            },
            teaRecord(){
                //查看当前教师的记录数据
            },
            havesee(id){
                //查看
                let that = this
                that.$http.post('/yii/resource/documention/teafileurl',{
                    id:id
                }).then(function(res){
                    console.log(res)
                    var originUrl='http://127.0.0.1/'+res.data.data//要预览文件的访问地址
                    window.open("http://127.0.0.1:8012/onlinePreview?url="+encodeURIComponent(originUrl),"_blank")
                })
            },
            downRecord(id){
                //下载
                let that = this
                that.$http.post('/yii/resource/documention/teafileurl',{
                    id:id
                }).then(function(res){
                    console.log(res)
                    var url='127.0.0.1/'+res.data.data
                    window.open('http://'+url,'_blank')
                })
            },
            teacherRecord(){
                //获取所有教师的记录数据
                let that = this
                that.$http.post('/yii/resource/resource/allteafile',{
                page:that.currentpage
                }).then(function(res){
                        console.log(res.data)
                       that.teafileData=res.data.data[0]
                       that.totalpage=res.data.data[1]
                    })
            },
            recordDetail(uname,tName){
              let that = this
                this.tName=tName
                that.$http.post('/yii/resource/resource/recorddetail',{
                    username:uname
                }).then(function(res){
                    console.log(res.data)
                    that.dialogVisible=true
                    that.filesDetail=res.data.data[0]
                    for(var i =0;i<res.data.data[1].length;i++){
                        that.recordUrl[i]='/zip'+res.data.data[1][i].url
                    }
                    console.log(that.recordUrl)
                })
            },
            bulkDownload(){
                //批量下载
                //批量下载并生成zip
                const data=this.recordUrl// 需要下载打包的路径, 可以是本地相对路径, 也可以是跨域的全路径
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
                        FileSaver.saveAs(content, this.tName+"见习记录.zip") // 利用file-saver保存文件
                    })
                })
            },
            pageChange: function (page) { // 分页
                if (this.currentpage != page) {
                    this.currentpage = page
                    console.log(this.currentpage)
                }
                if (this.activeName == "first") {
                    this.getTeaData()
                } else if (this.activeName == "second") {
                    this.getStuData()
                }else if(this.activeName == "third"){
                    this.getTutData()
                }
            },

            prepage: function (page) { // 上一页
                page--
                if (this.currentpage != page) {
                    this.currentpage = page
                    console.log(this.currentpage)
                }
                if (this.activeName == "first") {
                    this.getTeaData()
                } else if (this.activeName == "second") {
                    this.getStuData()
                }else if(this.activeName == "third"){
                    this.getTutData()
                }
            },

            nextpage: function (page) { // 下一页
                page++
                if (this.currentpage != page) {
                    this.currentpage = page
                    console.log(this.currentpage)
                }
                if (this.activeName == "first") {
                    this.getTeaData()
                } else if (this.activeName == "second") {
                    this.getStuData()
                }else if(this.activeName == "third"){
                    this.getTutData()
                }
            }
        },
        created() {
            this.teacherRecord()
        },
        computed: {
            // 计算属性：返回页码数组，这里会自动进行脏检查，不用$watch();
            pagenums: function () { // 分页
                // 初始化前后页边界
                let lowPage = 1
                let highPage = this.totalpage
                let pageArr = []
                if (this.totalpage > this.visiblepage) { // 总页数超过可见页数时，进一步处理；
                    let subVisiblePage = Math.ceil(this.visiblepage / 2)
                    if (this.currentpage > subVisiblePage && this.currentpage < this.totalpage - subVisiblePage + 1) { // 处理正常的分页
                        lowPage = this.currentpage - subVisiblePage
                        highPage = this.currentpage + subVisiblePage - 1
                    } else if (this.currentpage <= subVisiblePage) { // 处理前几页的逻辑
                        lowPage = 1
                        highPage = this.visiblepage
                    } else { // 处理后几页的逻辑
                        lowPage = this.totalpage - this.visiblepage + 1
                        highPage = this.totalpage
                    }
                }
                // 确定了上下page边界后，要准备压入数组中了
                while (lowPage <= highPage) {
                    pageArr.push(lowPage)
                    lowPage++
                }
                return pageArr
            }
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import "../../../common/css/pagination.css";
  .display1{
    border: solid 1px #E5E7E9;
    height: 600px;
    /*text-align: center;*/
    width: 98%;
    padding-left: 5px;
    padding-right: 5px;
    background-color: #fff;
  }
  .meeting{
    float:left;
    margin:14px 0 10px 0;
    font-weight: bold;
    background-color: #00AAFF;
    border:solid 1px #00AAFF;
    border-radius: 5px;
    width: 20%;
    padding:2px;
  }
  .btn3 {
    cursor: pointer;
    width: 80px;
    padding: 7px;
    font-size: 14px;
    border-radius: 3px;
    border: none;
    color: white;
    background-color: #338FFC;
    float: left;
    margin-left: 5px;
    margin-top: 17px;
    margin-bottom: 5px;
  }
</style>
