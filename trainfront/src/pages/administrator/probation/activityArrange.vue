<template>
<div class="display">
  <div class="main-content">
    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
      <el-tab-pane label="导师安排" name="first">
        <div class="display1">
          <div class="search">
            <div class="meeting">
              <el-input v-model="inputContent" placeholder="请输入教师或学生姓名" size="mini"></el-input>
            </div>
            <button class="btn3" v-on:click="searchArr()">搜索</button>
            <button class="btn3" type="text" @click="deleteALL()">全部清空</button>
            <button type="button" class="btn3" id="import-table" v-on:click="exportFile()">批量导出</button>
          </div>
          <table>
            <tr id="arrangeStastics">
              <th>序号</th>
              <th>实践类型</th>
              <th>学号</th>
              <th>学生姓名</th>
              <th>工号</th>
              <th>校内导师</th>
              <th>实践学校</th>
              <th>实践年级</th>
              <th>校外导师</th>
              <th>操作</th>
            </tr>
            <tr v-for="(item,index) in allocateList">
              <td>{{(currentpage-1)*8+index+1}}</td>
              <td v-if='item.type=="1"'>见习</td>
              <td v-if='item.status=="2"'>实习</td>
              <td>{{item.sno}}</td>
              <td>{{item.sName}}</td>
              <td>{{item.job_num}}</td>
              <td>{{item.tName}}</td>
              <td>{{item.school_name}}</td>
              <td>{{item.grade}}</td>
              <td>{{item.tutor_name}}</td>
              <td>
                <span style="cursor: pointer" @click="delone(item.username)"><el-button type="danger" icon="el-icon-delete" circle></el-button></span>
              </td>
            </tr>
          </table>
<!--          分页操作-->
          <div class="page">
            <ul class="pagination pagination-sm"><!--分页-->
              <li class="page-item" v-if="currentpage!=1">
                <span class="page-link" v-on:click="prepage(currentpage)">上一页</span>
              </li>
              <li class="page-item" v-for="(index,key1) in pagenums" :key="key1" v-bind:class="{ active: currentpage == index} ">
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
        </div>
      </el-tab-pane>
      <el-tab-pane label="见习历程管理" name="second">
        配置管理
      </el-tab-pane>
    </el-tabs>
  </div>
  <div class="statistics">
  </div>
</div>
</template>

<script>
    export default {
        name: "activityArrange",
        data(){
            return{
                activeName:'first',
                inputContent:'',
                allocateList:[],
                currentpage: 1, // 当前页
                visiblepage: 10, // 可见页数
                totalpage: '', // 总页数
            }
        },
        methods:{
            handleClick(tab, event) {
                console.log(tab, event);
                if(this.activeName=='first'){
                    this.getarrangeInfo()
                }
            },
            getarrangeInfo(){
                //获取见习导师安排的所有信息
                let that = this
                that.$http.post('/yii/probation/infomation/getarrange',{
                    page:that.currentpage
                }).then((res)=>{
                    console.log(res.data)
                    that.allocateList=res.data.data[0]
                    that.totalpage=res.data.data[1]
                })
            },
            searchArr(){
                //搜索导师安排信息
                let that = this
                that.$http.post('/yii/probation/infomation/queryarrange',
                    {
                        page:that.currentpage,
                        name:that.inputContent
                    }).then((res)=>{
                        console.log(res.data)
                        that.allocateList=res.data.data[0]
                        that.totalpage=res.data.data[1]
                        that.inputContent=''
                }).catch((err)=>{
                    console.log(err)
                })
            },
            delone(uName){
                //清空一条分配信息
                this.$confirm('此操作将永久删除该条信息, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=> {
                    let that = this
                    that.$http.post('/yii/probation/infomation/delonearr',
                        {
                            username: uName
                        }).then((res) => {
                        console.log(res.data)
                        if (res.data.message == "success") {
                            that.getarrangeInfo()
                            alert("删除成功！")
                        }
                    })
                }).catch((err)=>{
                    console.log(err)
                })
            },
            deleteALL(){
                //清空当前所有信息
                this.$confirm('此操作将永久删除所有信息, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    that.$http.post('/yii/probation/infomation/delallarr')
                        .then((res)=>{
                            console.log(res.data)
                            if (res.data.message == "success") {
                                that.getarrangeInfo()
                                alert("已全部清空！")
                            }
                        })
                }).catch((err)=>{
                    console.log(err)
                })
            },
            formatJson (filterVal, jsonData) {
                //数据格式化
                return jsonData.map(v => filterVal.map(j => v[j]))
            },
            exportFile(){
                //导师安排信息导出
                require.ensure([], () => {
                    const {export_json_to_excel} = require("../../../excel/Export2Excel.js");//引入文件
                    // excel表中要显示的标题头
                    const tHeader = ['序号','实践类型','学号','学生姓名','工号','校内导师','实践学校','实践年级','校外导师'];
                    // 导出的表头字段名
                    const filterVal =['aId','type','sno','sName','job_num','tName','school_name','grade','tutor_name']
                    const list = this.allocateList
                    const data = this.formatJson(filterVal, list);
                    export_json_to_excel(tHeader, data, "见习分配信息表");//此处的函数名要与Export2Excel.js中的对应
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
        created(){
            this.getarrangeInfo()
        },
        computed:{
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
</style>
