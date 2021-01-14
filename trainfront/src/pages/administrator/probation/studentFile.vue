<template>
  <div class="display1">
    <div class="search">
      <div class="meeting">
        <el-input v-model="teaname" placeholder="请输入指导教师姓名" size="mini"></el-input>
      </div>
      <button class="btn3" @click="theirData()">搜索</button>
      <button class="btn3" @click="showFile()">显示数据</button>
    </div>
    <table id="stufileStastics" v-show="notSearch">
      <tr>
        <th>序号</th>
        <th>学生姓名</th>
        <th>学号</th>
        <th>指导教师</th>
        <th>教师工号</th>
        <th>见习记录</th>
        <th>见习文档</th>
        <th>见习视频</th>
        <th>详情</th>
      </tr>
      <tr v-for="(item,index) in recordInfo">
        <td>{{index+1}}</td>
        <td>{{item.sName}}</td>
        <td>{{item.sno}}</td>
        <td>{{item.tName}}</td>
        <td>{{item.job_num}}</td>
        <td>{{item.num1}}</td>
        <td>{{item.num2}}</td>
        <td>{{item.num3}}</td>
        <td>
          <span>
             <el-button type="primary" plain size="small" @click="showRecord(item.username,item.sName)">记录详情</el-button>
             <el-button type="success" plain size="small" @click="fileShow(item.username,item.sName)">文档详情</el-button>
             <el-button type="warning" plain size="small" @click="showVideo(item.username,item.sName)">视频详情</el-button>
          </span>
        </td>
      </tr>
    </table>
<!--    搜索之后-->
    <table id="searchStastics" v-show="haveSearch">
      <tr>
        <th>序号</th>
        <th>学生姓名</th>
        <th>学号</th>
        <th>指导教师</th>
        <th>教师工号</th>
        <th>见习记录</th>
        <th>见习文档</th>
        <th>见习视频</th>
        <th>详情</th>
      </tr>
      <tr v-for="(item,index) in finalInfo">
        <td>{{index+1}}</td>
        <td>{{item.sName}}</td>
        <td>{{item.sno}}</td>
        <td>{{item.tName}}</td>
        <td>{{item.job_num}}</td>
        <td>{{item.num1}}</td>
        <td>{{item.num2}}</td>
        <td>{{item.num3}}</td>
        <td>
          <span>
             <el-button type="primary" plain size="small" @click="showRecord(item.username,item.sName)">记录详情</el-button>
             <el-button type="success" plain size="small" @click="fileShow(item.username,item.sName)">文档详情</el-button>
             <el-button type="warning" plain size="small" @click="showVideo(item.username,item.sName)">视频详情</el-button>
          </span>
        </td>
      </tr>
    </table>
<!--    显示记录详情-->
    <el-dialog title="记录详情" :visible.sync="dialogVisible" :before-close="handleClose" width="800px">
      <span><el-button type="danger" plain @click="fullDownload">批量下载</el-button></span>
      <table>
        <tr>
          <th style="width:5%">Id号</th>
          <th style="width:20%">类型</th>
          <th style="width:33%">文件名称</th>
          <th style="width:27%">上传时间</th>
          <th style="width:15%">操作</th>
        </tr>
        <tr v-for="item in recordDetail">
          <td>{{item.uId}}</td>
          <td v-if="item.kind==1">课堂教学观摩记录</td>
          <td v-if="item.kind==2">课堂教学观摩讨论记录</td>
          <td v-if="item.kind==3">班级管理见习记录</td>
          <td v-if="item.kind==4">教研活动见习记录</td>
          <td v-if="item.kind==5">见习生试讲教学设计</td>
          <td v-if="item.kind==6">见习生试讲听课记录</td>
          <td>{{item.filename}}</td>
          <td>{{item.createdTime}}</td>
          <td>
              <span style="display:flex">
               <span style="display:flex">
                <button style="border: 0px;float:left;margin-left:10px;cursor: pointer" @click="setRecord(item.uId)">查看与下载</button>
              </span>
              </span>
          </td>
        </tr>
      </table>
      <span slot="footer" class="dialog-footer">
     <el-button @click="dialogVisible= false">取 消</el-button>
      <el-button type="primary" @click="dialogVisible= false">确 定</el-button>
     </span>
    </el-dialog>
<!--    显示文档详情-->
    <el-dialog title="文档详情" :visible.sync="dialogVisible1" :before-close="handleClose" width="800px">
      <span><el-button type="danger" plain @click="fullDownload1">批量下载</el-button></span>
      <table>
        <tr>
          <th style="width:5%">Id号</th>
          <th style="width:20%">类型</th>
          <th style="width:33%">文档名称</th>
          <th style="width:27%">上传时间</th>
          <th style="width:15%">操作</th>
        </tr>
        <tr v-for="file in fileDetail">
          <td>{{file.fId}}</td>
          <td v-if="file.status==1">普通文档</td>
          <td v-if="file.status==2">见习总结</td>
          <td>{{file.filename}}</td>
          <td>{{file.submitTime}}</td>
          <td>
            <span style="display:flex">
                <button style="border: 0px;float:left;margin-left:10px;cursor: pointer" @click="seeFile(file.fId)">查看</button>
                <button style="border: 0px;float:left;margin-left:10px;cursor: pointer" @click="loadFile(file.fId)">下载</button>
              </span>
          </td>
        </tr>
      </table>
      <span slot="footer" class="dialog-footer">
           <el-button @click="dialogVisible1= false">取 消</el-button>
           <el-button type="primary" @click="dialogVisible1= false">确 定</el-button>
      </span>
    </el-dialog>
<!--    显示视频详情-->
    <el-dialog title="视频详情" :visible.sync="dialogVisible2" :before-close="handleClose" width="800px">
      <span><el-button type="danger" plain @click="fullDownload2">批量下载</el-button></span>
      <table>
        <tr>
          <th style="width:5%">Id号</th>
          <th style="width:33%">视频名称</th>
          <th style="width:27%">上传时间</th>
          <th style="width:35%">操作</th>
        </tr>
        <tr v-for="video in videoDetail">
          <td>{{video.vId}}</td>
          <td>{{video.videoname}}</td>
          <td>{{video.submitTime}}</td>
          <td>
             <span style="display:flex">
                <button style="border: 0px;margin-left:40px;cursor: pointer" @click="setVideo(video.vId)">查看与下载</button>
              </span>
          </td>
        </tr>
      </table>
      <span slot="footer" class="dialog-footer">
           <el-button @click="dialogVisible2= false">取 消</el-button>
           <el-button type="primary" @click="dialogVisible2= false">确 定</el-button>
      </span>
    </el-dialog>
<!--    局部统计分析显示-->
    <el-dialog  title="统计分析详情" :visible.sync="stasticsVisible" :before-close="handleClose" width="800px">
      <MultiBarChart :data="dataList" :title="stitle"></MultiBarChart>
      <span slot="footer" class="dialog-footer">
           <el-button @click="stasticsVisible= false">取 消</el-button>
           <el-button type="primary" @click="stasticsVisible= false">确 定</el-button>
      </span>
    </el-dialog>
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
    <!--    进行局部结果统计，有条件的显示-->
    <el-tag v-for="(item,index) in list" :key="index" style="margin:0px 5px;float:left;color:#080db7" v-show="notSearch">
      {{item.theme}}:{{item.num}}
    </el-tag>
    <el-button type="warning" round style="float:left" @click="partAnalyse" v-show="isAnalyse">数据统计</el-button>
  </div>
</template>

<script>
    import axios from 'axios'
    import JSZip from 'jszip'
    import FileSaver from 'file-saver'
    import MultiBarChart from "../../../components/MultiBarChart";
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
        name: "studentFile",
        components:{MultiBarChart},
        data(){
            return{
                currentpage: 1, // 当前页
                visiblepage: 10, // 可见页数
                totalpage: '', // 总页数
                teaname:'',
                recordInfo:[],
                finalInfo:[],
                searchInfo:[],//搜索之后的数据
                notSearch:true,
                haveSearch:false,
                isAnalyse:false,//是否进行局部统计分析
                recordDetail:[],
                fileDetail:[],
                videoDetail:[],
                dialogVisible:false,//记录
                dialogVisible1:false,//文档
                dialogVisible2:false,//视频
                recordUrl:[],//记录路径
                stuname:'',//当前学生姓名
                stuname1:'',
                stuname2:'',
                fileUrl:[],//文档路径
                videoUrl:[],//视频路径
                dataList:[],
                stasticsVisible:false,
                barData:[],//获取统计数据
                stitle:'学生见习数据统计',
                list:[
                    {
                        id:1,
                        theme:'见习记录',
                        num:0
                    },
                    {
                        id:2,
                        theme:'见习文档',
                        num:0
                    },
                    {
                        id:3,
                        theme:'见习视频',
                        num:0
                    }
                ]
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
            showRecord(uname,name){
                let that = this
                that.stuname=name
                that.$http.post('/yii/resource/documention/sturecorddetail',{
                    username:uname
                }).then(function(res){
                    console.log(res.data)
                    that.dialogVisible=true
                    that.recordDetail=res.data.data[0]
                    for(var i =0;i<res.data.data[1].length;i++){
                        that.recordUrl[i]='/zip'+res.data.data[1][i].url
                    }
                    console.log(that.recordUrl)
                })
            },
            fileShow(uname,name){
                let that = this
                that.stuname1=name
                console.log(that.stuname1)
                that.$http.post('/yii/resource/documention/stufiledetail',{
                    username:uname
                }).then(function(res){
                    console.log(res.data)
                    that.dialogVisible1=true
                    that.fileDetail=res.data.data[0]
                    for(var i =0;i<res.data.data[1].length;i++){
                        that.fileUrl[i]='/zip'+res.data.data[1][i].url
                    }
                    console.log(that.fileUrl)
                })
            },
            showVideo(uname,name){
                let that = this
                that.stuname2=name
                that.$http.post('/yii/resource/documention/stuvideodetail',{
                    username:uname
                }).then(function(res){
                    console.log(res.data)
                    that.dialogVisible2=true
                    that.videoDetail=res.data.data[0]
                    for(var i =0;i<res.data.data[1].length;i++){
                        that.videoUrl[i]='/zip'+res.data.data[1][i].url
                    }
                    console.log(that.videoUrl)
                })
            },
            showFile(){
                //显示学生见习期间的所有数据
                let that = this
                that.$http.post('/yii/resource/resource/allstufile',{
                    page:that.currentpage
                }).then(function(res){
                    console.log(res.data)
                    that.notSearch=true
                    that.haveSearch=false
                    that.isAnalyse=false
                    that.recordInfo=res.data.data[0]
                    for(let a=0;a<3;a++){
                        that.list[a].num=0
                    }
                     for(let i=0;i<that.recordInfo.length;i++){
                            that.list[0].num=that.list[0].num+that.recordInfo[i].num1
                            that.list[1].num=that.list[1].num+that.recordInfo[i].num2
                            that.list[2].num=that.list[2].num+that.recordInfo[i].num3
                        }
                        console.log(that.list)
                    console.log(that.recordInfo)
                    that.totalpage=res.data.data[1]
                })
            },
            theirData(){
                //查看某教师指导的学生的数据
                let that= this
                that.$http.post('/yii/resource/resource/stufile',{
                    name:that.teaname
                }).then(function(res){
                    console.log(res.data)
                    that.notSearch=false
                    that.haveSearch=true
                    that.isAnalyse=true
                    this.finalInfo=[]
                    for(let i =0;i<res.data.data.length;i++) {
                        that.recordInfo = res.data.data[i]
                        for(let j=0;j<that.recordInfo.length;j++){
                            this.finalInfo.push(that.recordInfo[j])
                        }
                    }
                    that.teaname=''
                    console.log(this.finalInfo)
                })
            },
            setRecord(id){
                //查看并下载记录
                let that = this
                that.$http.post('/yii/resource/resource/geturl',{
                    uId:id
                }).then(function(res){
                    console.log(res.data.data)
                    var url='127.0.0.1/'+res.data.data
                    var href='http://'+url
                    window.open(href,'_blank')
                })
            },
            setVideo(id){
                let that = this
                that.$http.post('/yii/resource/documention/thisurl',{
                    id:id
                }).then(function(res){
                    console.log(res.data)
                    //尝试用另一种方式预览视频
                    var originUrl='http://127.0.0.1/'+res.data.data
                    window.open("http://127.0.0.1:8012/onlinePreview?url="+encodeURIComponent(originUrl),"_blank")
                })
            },
            seeFile(id){
                //预览
                let that = this
                that.$http.post('/yii/resource/documention/theirurl',{
                    id:id
                }).then(function(res){
                    console.log(res.data)
                    var originUrl='http://127.0.0.1/'+res.data.data//要预览文件的访问地址
                    window.open("http://127.0.0.1:8012/onlinePreview?url="+encodeURIComponent(originUrl),"_blank")
                })
            },
            loadFile(id){
                //下载
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
                //记录批量下载
                const data=this.recordUrl
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
                        FileSaver.saveAs(content, this.stuname+"见习记录.zip") // 利用file-saver保存文件
                    })
                })
            },
            fullDownload1(){
                //文档批量下载
                const data=this.fileUrl
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
                        FileSaver.saveAs(content, this.stuname1+"见习文档.zip") // 利用file-saver保存文件
                    })
                })
            },
            fullDownload2(){
                //视频批量下载
                const data=this.videoUrl
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
                        FileSaver.saveAs(content, this.stuname2+"见习视频.zip") // 利用file-saver保存文件
                    })
                })
            },
            partAnalyse(){
               //局部的统计分析
                this.stasticsVisible=true
                console.log(this.finalInfo)
                let that= this
                that.barData=this.finalInfo
                for(let j =0;j<that.barData.length;j++){
                    that.dataList.push({
                        name:that.barData[j].sName,
                        num1:that.barData[j].num1,
                        num2:that.barData[j].num2,
                        num3:that.barData[j].num3,
                    })
                }
                console.log(that.dataList)
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
        },
        created() {
            this.showFile()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import "../../../common/css/pagination.css";
  .display1{
    border: solid 1px #E5E7E9;
    height: 600px;
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
