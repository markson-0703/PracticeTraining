<template>
<div class="display">
  <div class="display1">
    <div class="search">
    <div class="meeting">
      <el-input v-model="inputUser" placeholder="请输入用户名" size="mini"></el-input>
    </div>
    <button class="btn3 el-icon-search" v-on:click="searchUser()">搜索</button>
    <button type="button" class="btn3" id="import-table" v-on:click="exportFile()">批量导出</button>
    <table id="userStastics">
    <tr>
      <th>序号</th>
      <th>用户名</th>
      <th>身份</th>
      <th>状态</th>
      <th>操作</th>
    </tr>
      <tr v-for="(item,index) in userList">
        <td>{{(currentpage-1)*8+index+1}}</td>
        <td>{{item.username}}</td>
        <td v-if='item.role==1'>管理员</td>
        <td v-if='item.role==2'>校内教师</td>
        <td v-if='item.role==3'>学生</td>
        <td v-if='item.role==4'>校外教师</td>
        <td v-if='item.status==0'>无效</td>
        <td v-if='item.status==1'>有效</td>
        <td><span @click="deleteUser(item.username,item.role)" style="cursor: pointer"><i class="el-icon-delete"></i>删除</span></td>
      </tr>
  </table>
  </div>
    <div class="page">
      <ul class="pagination pagination-sm"><!--分页-->
        <li class="page-item" v-if="currentpage!=1">
          <span class="page-link" v-on:click="prepage(currentpage)" style="cursor: pointer">上一页</span>
        </li>
        <li class="page-item" v-for="(index,key1) in pagenums" :key="key1" v-bind:class="{ active: currentpage == index} ">
          <span class="page-link" v-on:click="pageChange(index)" style="cursor: pointer">{{ index }}</span>
        </li>
        <li class="page-item" v-if="currentpage!=totalpage">
          <span class="page-link" v-on:click="nextpage(currentpage)" style="cursor: pointer">下一页</span>
        </li>
        <li class="page-item">
          <span class="page-link">共<i>{{totalpage}}</i>页</span>
        </li>
      </ul>
    </div>
  </div>
</div>

</template>

<script>
    export default {
        name: "user",
        data(){
            return{
                userList:[],
                inputUser:'',//搜索关键词
                currentpage: '1',
                totalpage: '',
                visiblepage: 10,
            }
        },
        methods:{
            //从后台获取用户信息
            getuserDate(){
                let that=this
                that.$http.post('/yii/probation/probation/getusers?page=1',{ page: that.currentpage})
                    .then((res=>{
                    console.log(res.data)
                    that.userList=res.data.data[0]
                    that.totalpage=res.data.data[1]
                    console.log(that.totalpage)
                }))
            },
            searchUser(){
                let that =this
                that.$http.post('/yii/probation/probation/queryuser',{
                       username:that.inputUser,
                       page:that.currentpage
                }).then((res)=>{
                    console.log(res.data)
                    console.log(res)
                    that.userList=res.data.data[0]
                    that.totalpage=res.data.data[1]
                }).catch((err)=>{
                    console.log(err)
                })
            },
            deleteUser(uName,role){
                this.$confirm('此操作将永久删除该用户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    that.$http.post('/yii/probation/probation/deleteuser',{uname:uName,role:role})
                        .then((res)=>{
                            console.log(res.data)
                            if(res.data.message=="success"){
                                that.getuserDate()
                                alert("删除用户成功！")
                            }
                        }).catch((err)=>{
                            console.log(err)
                    })
                })
           },
            formatJson (filterVal, jsonData) {
                return jsonData.map(v => filterVal.map(j => v[j]))
            },
            exportFile(){
                //实现批量导出
                require.ensure([], () => {
                    const {export_json_to_excel} = require("../../../excel/Export2Excel.js");//引入文件
                    // excel表中要显示的标题头
                    const tHeader = ['序号','用户名','身份','状态'];
                    // 导出的表头字段名
                    const filterVal =['id','username','role','status']
                    const list = this.userList
                    const data = this.formatJson(filterVal, list);
                    export_json_to_excel(tHeader, data, "用户信息表");//此处的函数名要与Export2Excel.js中的对应
                })

            },
            pageChange: function (page) { // 分页
                if (this.currentpage != page) {
                    this.currentpage = page
                    console.log(this.currentpage)
                }
                this.getuserDate(this.currentpage)
            },

            prepage: function (page) { // 上一页
                page--
                if (this.currentpage != page) {
                    this.currentpage = page
                    console.log(this.currentpage)
                }
                this.getuserDate(this.currentpage)
            },

            nextpage: function (page) { // 下一页
                page++
                if (this.currentpage != page) {
                    this.currentpage = page
                    console.log(this.currentpage)
                }
                this.getuserDate(this.currentpage)
            }
        },
        created() {
            this.getuserDate()
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
  .display {
    padding-top: 10px;
  }
  .display1 {
    border: solid 2px #e0e0e0;
    height: 600px;
    width: 98%;
    padding-left: 10px;
    padding-right: 10px;
    background-color: #fff;
  }
  button{
    cursor: pointer;
  }
  .btn3 {
    width: 100px;
    padding: 7px;
    font-size: 14px;
    border-radius: 5px;
    border: none;
    color: white;
    background-color: #11a8f3;
    float: left;
    margin-left: 15px;
    margin-top: 17px;
    margin-bottom: 5px;
  }
  .meeting{
    float:left;
    margin:14px 0 10px 0;
    font-weight: bold;
    background-color: #11a8f3;
    border:solid 1px #11a8f3;
    border-radius: 5px;
    width: 20%;
    padding:2px;
  }

</style>
