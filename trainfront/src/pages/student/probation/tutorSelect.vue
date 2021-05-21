<template>
  <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
    <marquee><h2>请从以下两种方式中任选一种进行导师选择！</h2></marquee>
    <el-tab-pane label="按导师选择" name="first">
      <div class="display1">
        <div class="select-s">
<!--          这部分学生选择本校导师-->
          <div class="meeting">
            <el-input v-model="inputtea" placeholder="请输入你想搜索的教师" size="mini"></el-input>
          </div>
          <button class="btn3" v-on:click="searchTea()">搜索</button>
          <table id="teaStastics">
            <tr>
              <th>序号</th>
              <th>用户名</th>
              <th>教师姓名</th>
              <th>工号</th>
              <th>联系电话</th>
              <th>邮箱</th>
              <th>职称</th>
              <th>操作</th>
            </tr>
            <tr v-for="(item,index) in teacherList">
              <td>{{(currentpage-1)*8+index+1}}</td>
              <td>{{item.username}}</td>
              <td>{{item.tName}}</td>
              <td>{{item.job_num}}</td>
              <td>{{item.contactPhone}}</td>
              <td>{{item.email}}</td>
              <td>{{item.rank}}</td>
              <td>
                <span style="cursor: pointer" @click="selectTea(item.username,item.tName,item.job_num)"> <el-button type="primary" plain>选择</el-button></span>
              </td>
            </tr>
          </table>
          <!--        实现分页效果-->
          <div class="page">
            <ul class="pagination pagination-sm"><!--分页-->
              <li class="page-item" v-if="currentpage!=1">
                <span class="page-link" v-on:click="prepage(currentpage)">上一页</span>
              </li>
              <li class="page-item" v-for="(index,key2) in pagenums" :key="key2" v-bind:class="{ active: currentpage == index} ">
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
        <el-divider></el-divider>
        <div class="show-s" v-show="flag">
<!--          这部分学生选择见习学校里面的导师-->
         <div>
           <label>校内导师</label>
           <input class="inputdiv" type="text" v-model="teaForm.tName">
         </div>
          <div>
            <label>工&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp号</label>
            <input class="inputdiv" type="text" v-model="teaForm.job_num">
          </div>
          <div>
            <label>见习点</label>
            <select class="inputdiv" v-model="site" id="selectSite" style="font-size:15px;width:180px;" @change="handleChange" >
              <option disabled value="">--请选择--</option>
              <option v-bind:value="Item.site" v-for="Item in siteList" >{{Item.site}}</option>
            </select>
          </div>
          <div>
            <label>见习点导师</label>
            <select class="inputdiv" v-model="tutor" id="selectTut" style="font-size:15px;width:180px;" @change="handleChange1">
              <option disabled value="">--请选择--</option>
              <option v-bind:value="Item.tName" :key="Item.tId" v-for="Item in tutorList" >{{Item.tName}}</option>
            </select>
          </div>
        </div>
        <div class="add-btn" v-show="flag">
          <button type="submit" class="btn1 icon-duigou" v-on:click="submit()" style="cursor: pointer">确认
          </button>
        </div>
        <el-divider></el-divider>
        <div class="show-result" v-show="showResult">
        <p v-if="this.ischoosen==0">提交成功，请等待老师审核</p>
        <p v-if="this.ischoosen==1">{{this.finaltutor}}老师审核成功,你的见习地点为{{this.finalsite}}</p>
          <div v-if="this.leader==1">
            <p>你是该见习点的小组长,组员包括:</p>
            <ul v-for="item in memberList">
              <li>{{item.sName}}</li>
            </ul>
          </div>
        </div>
      </div>
    </el-tab-pane>
    <el-tab-pane label="按学校选择" name="second">
      <div class="display1">
        <div class="select-school">
          <!--          这部分学生选择外校-->
          <div class="meeting">
            <el-input v-model="inputsite" placeholder="请输入你想搜索的学校" size="mini"></el-input>
          </div>
          <button class="btn3" v-on:click="searchSite()">搜索</button>
          <table id="schoolStastics">
            <tr>
              <th>序号</th>
              <th>学校</th>
              <th>操作</th>
            </tr>
            <tr v-for="(item,index) in schoolList">
              <td>{{(currentpage-1)*8+index+1}}</td>
              <td>{{item.site}}</td>
              <td>
                <span style="cursor: pointer" @click="selectSchool(item.tName,item.job_num,item.site)"> <el-button type="primary" plain>选择</el-button></span>
              </td>
            </tr>
          </table>
          <!--        实现分页效果-->
          <div class="page">
            <ul class="pagination pagination-sm"><!--分页-->
              <li class="page-item" v-if="currentpage!=1">
                <span class="page-link" v-on:click="prepage(currentpage)">上一页</span>
              </li>
              <li class="page-item" v-for="(index,key2) in pagenums" :key="key2" v-bind:class="{ active: currentpage == index} ">
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
        <el-divider></el-divider>
        <div class="select-tea" v-show="flag1">
          <!--          这部分学生选择见习学校的导师，再自动匹配校内导师-->
          <div>
            <label>学校名称</label>
            <input class="inputdiv" type="text" v-model="teaForm1.school">
          </div>
          <div>
            <label>校内导师</label>
            <input class="inputdiv" type="text" v-model="teaForm1.tName">
          </div>
          <div>
            <label>工&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp号</label>
            <input class="inputdiv" type="text" v-model="teaForm1.job_num">
          </div>
          <div>
            <label>见习点导师</label>
            <select class="inputdiv" v-model="teaForm1.tutor" id="chooseTut" style="font-size:15px;width:180px;" @change="handleChange2">
              <option disabled value="">--请选择--</option>
              <option v-bind:value="Item.tName" :key="Item.tId" v-for="Item in tutorList1" >{{Item.tName}}</option>
            </select>
          </div>
        </div>
        <div class="add-btn" v-show="flag1">
          <button type="submit" class="btn1 icon-duigou" v-on:click="submit1()" style="cursor: pointer">确认
          </button>
        </div>
        <el-divider></el-divider>
        <div class="show-result" v-show="showResult1">
          <p v-if="this.ischoosen==0">提交成功，请等待老师审核</p>
          <p v-if="this.ischoosen==1">{{this.finaltutor}}老师审核成功,你的见习地点为{{this.finalsite}}</p>
          <div v-if="this.leader==1">
            <p>你是该见习点的小组长,组员包括:</p>
             <ul v-for="item in memberList">
               <li>{{item.sName}}</li>
             </ul>
          </div>
        </div>
      </div>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
    export default {
        name: "tutorSelect",
        data(){
            return{
                activeName: 'first',
                flag:false,
                flag1:false,
                showResult:false,
                showResult1:false,
                inputtea:'',
                inputsite:'',
                teacherList:[],
                schoolList:[],
                memberList:[],
                teaForm:{
                    tName:'',//校内导师的姓名
                    job_num:''//校内导师的工号
                },
                teaForm1:{
                    tName:'',//校内导师的姓名
                    job_num:'',//校内导师的工号
                    school:'',//地点
                    tutor:'',
                    jno:''
                },
                site:'',//select选中的值
                tutor:'',
                jno:'',//校外导师工号
                username:'',//学生用户名
                sno:'',//学生学号
                sName:'',//学生姓名
                ischoosen:0,//鉴定导师是否同意
                finaltutor:'',//最终的导师
                finalsite:'',//最终的见习点
                leader:0,//是否被选为了组长
                siteList:[],//每个老师对应的见习点列表
                tutorList:[],//见习点导师列表
                tutorList1:[],
                currentpage: 1, // 当前页
                visiblepage: 10, // 可见页数
                totalpage: '', // 总页数
            }
        },
        methods: {
            checkShow(){
                //如果学生用户已经申请了导师，则相应界面显示
                let that = this
                that.$http.post('/yii/probation/select/ischoose',{
                    username:that.username
                }).then((res)=>{
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.ischoosen=res.data.data.ischecked
                        that.finaltutor=res.data.data.tName
                        that.finalsite=res.data.data.school_name
                        that.leader=res.data.data.leader
                        alert("你已申请过见习导师，请勿重复选择！")
                        this.showResult=true
                        this.showResult1=true
                    }else if(res.data.message=="学生还未申请导师"){
                        alert("你还未申请过导师，请任选一种方式进行导师选择！")
                    }
                })
            },
            getMember(){
                //获取小组成员
                let that = this
                that.$http.post('/yii/probation/select/getmember',{
                    username:this.username
                }).then((res)=>{
                    console.log(res.data)
                    that.memberList=res.data.data
                })
            },
            handleClick(tab, event) {
                console.log(tab, event);
            },
            handleChange(){
                console.log(document.getElementById('selectSite').value)
                this.site=document.getElementById('selectSite').value
                this.getTutorData(this.site)
            },
            handleChange1(){
                console.log(document.getElementById('selectTut').value)
                this.tutor=document.getElementById('selectTut').value
                //获取该导师的工号
                this.$http.post('/yii/probation/select/getonejno',{
                    tName:this.tutor
                }).then((res)=>{
                    console.log(res.data)
                    this.jno=res.data.data.job_num
                    console.log(this.jno)
                })
            },
            handleChange2() {
                console.log(document.getElementById('chooseTut').value)
                this.teaForm1.tutor=document.getElementById('chooseTut').value
                //获取该导师的工号
                this.$http.post('/yii/probation/select/getonejno',{
                    tName:this.teaForm1.tutor
                }).then((res)=>{
                    console.log(res.data)
                    this.teaForm1.jno=res.data.data.job_num
                    console.log(this.teaForm1.jno)
                })
            },
            getStuInfo(){
                //获取学生的学号和姓名
                let that = this
                that.$http.post('/yii/probation/select/getstuinfo',{
                    username:this.username
                }).then((res)=>{
                    console.log(res.data)
                    this.sno=res.data.data.sno
                    this.sName=res.data.data.sName
                })
            },
            selectTea(uName,tName,jno){
                this.flag=true
                this.teaForm.tName=tName
                this.teaForm.job_num=jno
                this.getSiteData(tName,jno)
            },
            selectSchool(name,jno,site){
                this.flag1=true
                this.teaForm1.tName=name
                this.teaForm1.job_num=jno
                this.teaForm1.school=site
                this.getTutorData1(site)
            },
            submit(){
               //选择完见习点和导师后提交
                let that = this
                that.$http.post('/yii/probation/select/tutorarrange',{
                    username:that.username,
                    sno:that.sno,
                    sName:that.sName,
                    tutor:that.tutor,
                    jno:that.jno,
                    tName:that.teaForm.tName,
                    job_num:that.teaForm.job_num,
                    site:that.site
                }).then((res)=>{
                    console.log(res.data)
                    that.showResult=true
                    that.showResult1=true
                    that.ischoosen=res.data.data.ischecked
                    console.log(that.ischoosen)
                    console.log(res.data.data.ischecked)
                    if(res.data.message=="success"){
                       alert("提交成功")
                    }else if(res.data.message=="success"){
                        alert("提交失败")
                    }else{
                        alert("请勿重复提交")
                    }
                })
            },
            submit1(){
                let that = this
                that.$http.post('/yii/probation/select/tutorarrange', {
                    username:that.username,
                    sno:that.sno,
                    sName:that.sName,
                    tutor:that.teaForm1.tutor,
                    jno:that.teaForm1.jno,
                    tName:that.teaForm1.tName,
                    job_num:that.teaForm1.job_num,
                    site:that.teaForm1.school
                }).then((res)=>{
                    console.log(res.data)
                    that.showResult=true
                    that.showResult1=true
                    that.ischoosen=res.data.data.ischecked
                    if(res.data.message=="success"){
                        alert("提交成功")
                    }else if(res.data.message=="success"){
                        alert("提交失败")
                    }else{
                        alert("请勿重复提交")
                    }
                })
            },
            getSiteData(name,jno){
                //获取导师相应的见习点
                console.log(name)
                console.log(jno)
                let that = this
                that.$http.post('/yii/probation/select/getsitedata',{
                    tName:name,
                    job_num:jno
                }).then((res)=>{
                    console.log(res.data)
                    that.siteList=res.data.data
                })
            },
            getTutorData1(site){
                //获取见习点相应的负责老师
                console.log(site)
                let that = this
                that.$http.post('/yii/probation/select/gettutor',{
                    school:site
                }).then((res)=>{
                    console.log(res.data)
                    that.tutorList1=res.data.data
                })
            },
            getTutorData(site){
                console.log(site)
                //获取见习点相应的负责老师
                let that = this
                that.$http.post('/yii/probation/select/gettutdata',{
                    site:site
                }).then((res)=>{
                    console.log(res.data)
                    that.tutorList=res.data.data
                })
            },
            getTeaData(){
                //获取教师数据
                let that = this
                that.$http.post('/yii/probation/probation/gettea',{page:that.currentpage})
                    .then((res)=>{
                        console.log(res.data)
                        that.teacherList=res.data.data[0]
                        that.totalpage=res.data.data[1]
                        console.log(that.currentpage)
                    })
            },
            getSchoolData(){
                //获取学校数据
                let that = this
                that.$http.post('/yii/probation/probation/getschool',{page:that.currentpage})
                    .then((res)=>{
                        console.log(res.data)
                        that.schoolList=res.data.data[0]
                        that.totalpage=res.data.data[1]
                    })
            },
            searchTea(){
                //搜索教师信息
                let that =this
                that.$http.post('/yii/probation/probation/querytea',{
                    page:that.currentpage,
                    tName:that.inputtea
                }).then((res)=>{
                    console.log(res.data)
                    that.teacherList=res.data.data[0]
                    that.totalpage =res.data.data[1]
                }).catch((err)=>{
                    console.log(err)
                })
            },
            searchSite(){
                //搜索学校信息
                let that = this
                that.$http.post('/yii/probation/probation/querysite',{
                    page:that.currentpage,
                    school:that.inputsite
                }).then((res)=>{
                    console.log(res.data)
                    that.schoolList=res.data.data[0]
                    that.totalpage =res.data.data[1]
                }).catch((err)=>{
                    console.log(err)
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
        },
        created() {
            this.getTeaData()
            this.getSchoolData()
            this.username=this.$store.getters.getsName
            this.getStuInfo()
            this.checkShow()
            this.getMember()
            console.log(this.$store.getters.getsName)//获取当前学生用户的用户名
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import "../../../common/css/pagination.css";
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
    margin-top: 15px;
    margin-bottom: 5px;
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
  .show-s,.select-tea {
    padding: 30px;
    display: flex;
    flex-wrap: wrap;
    align-content: flex-start
  }
  .show-s > div {
    width: 25%;
  }
  .select-tea> div {
    width: 25%;
  }
  .inputdiv {
    margin-bottom: 25px;

  }
  .show-s>div>label{
    font-weight: bolder;
  }
  .select-tea>div>label{
    font-weight: bolder;
  }
  .btn1{
    width:120px;
    padding:7px;
    font-size: 16px;
    border-radius: 3px;
    border:none;
    color:white;
    background-color:#595f67 ;
    margin-left: 20px;
    margin-top: 0px;
    /*margin-bottom: 5px;*/
  }
  h2{
    background-color:seagreen;
    color:white;
    position: relative;
    font-weight: bolder;
    width:fit-content;
  }
  .show-result p{
    font-weight: bolder;
  }
  .show-result ul{
    list-style: none;
  }
  .show-result li{
    display: inline;
    /*float:left;*/
  }
</style>
