<template>
<!--    管理员实习部分的用户管理-->
  <div class="display">
    <div class="display1">
      <div class="search">
        <div class="meeting">
          <el-input v-model="inputRole" placeholder="请输入身份类别" size="mini"></el-input>
        </div>
        <button class="btn3 el-icon-s-data" @click="showData()">显示数据</button>
        <button class="btn3 el-icon-search" v-on:click="searchUser()">搜索</button>
        <button type="button" class="btn3 el-icon-menu" id="import-table" v-on:click="exportFile()">批量导出</button>
        <button class="btn3 el-icon-delete" v-on:click="bulkDelete()">批量删除</button>
        <table id="clientStastics">
          <tr>
            <th>选择</th>
            <th>序号</th>
            <th>用户名</th>
            <th>身份</th>
            <th>状态</th>
            <th>操作</th>
          </tr>
          <tr v-for="(item,index) in userList">
            <td>
              <el-checkbox v-model="item.id" @change="addItem(item.id,item.username)"></el-checkbox>
            </td>
            <td>{{(currentpage-1)*8+index+1}}</td>
            <td>{{item.username}}</td>
            <td v-if='item.role==1'>管理员</td>
            <td v-if='item.role==2'>校内教师</td>
            <td v-if='item.role==3'>学生</td>
            <td v-if='item.role==4'>校外教师</td>
            <td v-if='item.status==0'>实习部分无效</td>
            <td v-if='item.status==1'>实习部分有效</td>
            <td><span @click="deleteUser(item.username,item.role)" style="cursor: pointer"><i class="el-icon-delete"></i>删除</span></td>
          </tr>
        </table>
      </div>
      <!--分页-->
      <div class="page">
        <ul class="pagination pagination-sm">
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
                inputRole:'',//输入搜索关键词，包括学生，校内教师等
                userList:[],
                currentpage: '1',
                totalpage: '',
                visiblepage: 10,
                chooseList:[],
                checked:''
            }
        },
        methods:{
            addItem(val,uname){
                //添加选择对象
                console.log(val,uname)
                let that = this
                if(val==true){
                    that.chooseList.push(uname)
                }else if(val==false){
                    that.chooseList.pop(uname)
                }
                console.log(that.chooseList)
            },
            bulkDelete(){
                //批量删除
                this.$confirm('此操作将彻底删除这批用户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    if(that.chooseList.length==0){
                        alert("还未选择删除对象！")
                    }else{
                        that.$http.post('/yii/practice/practice/bulkdelusers',{
                            member:that.chooseList
                        }).then(function(res){
                            console.log(res.data)
                            if(res.data.message=="success"){
                                alert('批量删除成功!')
                                that.getuserDate()
                            }else{
                                alert('批量删除失败!')
                            }
                        })
                    }
                }).catch(function(err){
                    console.log(err)
                })
            },
            //获取用户信息
            getuserDate(){
                let that = this
                that.$http.post('/yii/practice/practice/userdata?page=1',{
                    page:that.currentpage
                }).then(function(res){
                    console.log(res.data)
                    that.userList=res.data.data[0]
                    that.totalpage=res.data.data[1]
                })
            },
            showData(){
                //显示完整的用户数据
                this.getuserDate()
            },
            searchUser(){
                //搜索用户
                let that =this
                if(that.inputRole=="校内教师"){
                    that.inputRole=2
                }else if(that.inputRole=="学生"){
                    that.inputRole=3
                }else if(that.inputRole=="校外教师"){
                    that.inputRole=4
                }else{
                    alert("请输入正确的搜索关键词！")
                    return false
                }
                that.$http.post('/yii/practice/practice/queryuser',{
                    role:that.inputRole,
                    page:that.currentpage
                }).then((res)=>{
                    console.log(res.data)
                    that.userList=res.data.data[0]
                    that.totalpage=res.data.data[1]
                    that.inputRole=''
                }).catch((err)=>{
                    console.log(err)
                })
            },
            deleteUser(uName,role){
                this.$confirm('此操作将从实习平台中永久删除该用户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    that.$http.post('/yii/practice/practice/deleteuser',{uname:uName,role:role})
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

<style scoped>
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
