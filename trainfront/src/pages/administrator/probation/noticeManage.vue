<template>
<div class="display">
  <span style="margin-top:10px;margin-left:20px;color:#337ab7;font-weight: bolder;float:left;">
    <i class="el-icon-s-comment"></i>
    消息管理 Message Management
  </span>
  <div class="meeting">
    <el-input v-model="inputobj" placeholder="请输入发布对象" size="mini"></el-input>
  </div>
  <button class="btn3" v-on:click="getNoticeData()">数据显示</button>
  <button class="btn3" v-on:click="searchNotice()">搜索</button>
  <button class="btn3" type="text" @click="addNotice()">添加</button>
  <div class="notice">
    <el-tooltip class="item" effect="dark" content="清除系统对象的所有消息" placement="top-start">
      <el-button type="primary" size="small" icon="el-icon-delete" style="float:left;margin:10px 0 5px 15px" @click="delALL()"></el-button>
    </el-tooltip>
<!--    添加通知-->
    <el-dialog title="添加一条新通知" :visible.sync="dialogFormVisible">
      <el-form :model="noticeForm">
        <el-form-item label="消息内容" :label-width="formLabelWidth">
          <el-input type="textarea" style="width: 350px;" v-model="noticeForm.content" auto-complete="off"></el-input>
        </el-form-item>
        <el-form-item label="发布对象" :label-width="formLabelWidth">
          <select v-model="noticeForm.ojbect" style="font-size:15px;width:180px;" >
            <option value=2>校内教师</option>
            <option value=3>学生</option>
            <option value=4>校外教师</option>
          </select>
        </el-form-item>
      </el-form>
      <div slot="footer" style="align-content: center" class="dialog-footer">
        <el-button type="primary" @click="submit()">提交</el-button>
        <el-button @click="closeDialog()">取消</el-button>
      </div>
    </el-dialog>
<!--    修改通知-->
    <el-dialog title="修改通知内容" :visible.sync="dialogFormVisible1">
      <el-form :model="noticeForm">
        <el-form-item label="消息内容" :label-width="formLabelWidth">
          <el-input type="textarea" style="width: 350px;" v-model="noticeForm.content" auto-complete="off"></el-input>
        </el-form-item>
        <el-form-item label="发布对象" :label-width="formLabelWidth">
          <select v-model="noticeForm.ojbect" style="font-size:15px;width:180px;" >
            <option value=2>校内教师</option>
            <option value=3>学生</option>
            <option value=4>校外教师</option>
          </select>
        </el-form-item>
      </el-form>
      <div slot="footer" style="align-content: center" class="dialog-footer">
        <el-button type="primary" @click="updateNotice()">更新</el-button>
        <el-button class="el-button" @click="reset()">重置</el-button>
        <el-button @click="closeDialog1()">取消</el-button>
      </div>
    </el-dialog>
<table id="noticeStastics">
  <tr>
    <th style="width: 5%">序号</th>
    <th style="width: 50%">消息内容</th>
    <th style="width: 15%">发布对象</th>
    <th style="width: 20%">操作</th>
    <th style="width: 10%">消息推送</th>
  </tr>
  <tr v-for="(item,index) in noticeData">
    <td>{{(currentpage-1)*8+index+1}}</td>
    <td>{{item.content}}</td>
    <td v-if='item.ojbect=="2"'>校内教师</td>
    <td v-if='item.ojbect=="3"'>学生</td>
    <td v-if='item.ojbect=="4"'>校外教师</td>
    <td>
      <span style="cursor: pointer"><el-button type="primary" icon="el-icon-edit" circle @click="alterNotice(item.nId)"></el-button></span>
      <span style="cursor: pointer"><el-button type="danger" icon="el-icon-delete" circle @click="delNotice(item.nId)"></el-button></span>
    </td>
    <td>
      <span style="cursor: pointer">
        <el-button type="warning" size="small" icon="el-icon-check" round @click="pushNotice(item.nId,item.ojbect)"></el-button>
      </span>
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
</div>
</div>
</template>

<script>
    export default {
        name: "noticeManage",
        data(){
            return{
                inputobj:'',
                dialogFormVisible:false,
                dialogFormVisible1:false,
                noticeForm:{
                    id:0,
                    content:'',
                    ojbect:0
                },
                formLabelWidth: '120px',
                noticeData:[],
                currentpage: 1, // 当前页
                visiblepage: 10, // 可见页数
                totalpage: '' // 总页数
            }
        },
        methods:{
            getNoticeData(){
                //获取消息数据
                let that= this
                that.$http.post('/yii/notice/notice/noticedata',{
                page:that.currentpage
                }).then(function(res){
                  console.log(res.data)
                    that.noticeData=res.data.data[0]
                    that.totalpage=res.data.data[1]
                    console.log(that.noticeData)
                    })
            },
            delNotice(id){
                //删除通知内容
                this.$confirm('是否永久删除该条消息, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    that.$http.post('/yii/notice/notice/deletenotice',{
                        id:id
                    }).then(function(res){
                        console.log(res.data)
                        if(res.data.message=="success"){
                            that.getNoticeData()
                            alert("删除消息成功！")
                        }
                    })
                }).catch((err)=>{
                    console.log(err)
                })
            },
            delALL(){
               //删除所有消息，即各表的notice置为0，且删除该字段，notice表的状态置为0
                let that = this
                that.$http.post('/yii/notice/notice/delallnotice')
                    .then(function(res){
                        console.log(res.data)
                        if(res.data.message=="success"){
                            alert('系统消息清除成功!')
                            that.getNoticeData()
                        }else{
                            alert('系统消息清除失败!')
                        }
                    })
            },
            submit(){
                //提交新通知
                this.$confirm('确认增加这条新通知?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    if(that.noticeForm.content!=''&&that.noticeForm.ojbect!=''){
                        that.$http.post('/yii/notice/notice/insertnotice',{
                            content:that.noticeForm.content,
                            object:that.noticeForm.ojbect,
                            typeId:1
                        }).then(function(res){
                            console.log(res.data)
                            if (res.data.message = "success") {
                                alert("新消息添加成功！")
                                that.dialogFormVisible = false
                                that.getNoticeData()
                            } else {
                                alert("新消息添加失败！")
                            }
                        })
                    }else{
                        alert('新增内容不能为空!')
                    }
                }).catch((err)=>{
                    console.log(err)
                })
            },
            alterNotice(id){
                //修改通知内容
                let that = this
                that.$http.post('/yii/notice/notice/onenotice',{
                    id:id
                }).then(function(res){
                    console.log(res.data)
                    that.noticeForm.id=res.data.data.nId
                    that.noticeForm.content=res.data.data.content
                    that.noticeForm.ojbect=res.data.data.ojbect
                    that.dialogFormVisible1=true
                })
            },
            updateNotice(){
                //更新修改内容
                console.log(this.noticeForm.ojbect)
                let that = this
                that.$http.post('/yii/notice/notice/updatenotice',{
                    id:that.noticeForm.id,
                    content:that.noticeForm.content,
                    ojbect:that.noticeForm.ojbect
                }).then(function(res){
                    console.log(res.data)
                    if (res.data.message=="success"){
                        alert('内容修改成功')
                    }else if(res.data.message=="failure"){
                        alert('内容修改失败')
                    }
                    that.getNoticeData()
                    that.dialogFormVisible1=false
                }).catch((err)=>{
                    console.log(err)
                })
            },
            searchNotice(){
                //搜索消息
                if(this.inputobj=="校内教师"){
                    this.inputobj=2
                }else if(this.inputobj=="学生"){
                    this.inputobj=3
                }else if(this.inputobj=="校外教师"){
                    this.inputobj=4
                }
                let that = this
                that.$http.post('/yii/notice/notice/querynotice',{
                    page:that.currentpage,
                    object:that.inputobj
                }).then(function(res){
                    console.log(res.data)
                    that.noticeData=res.data.data[0]
                    that.totalpage=res.data.data[1]
                    if(res.data.message=="success"){
                        that.inputobj=''
                    }
                })
            },
            addNotice(){
                //添加消息
                let that = this
                that.dialogFormVisible=true
                that.noticeForm.content=''
                that.noticeForm.ojbect=''
            },
            reset(){
                this.noticeForm.content='';
                this.noticeForm.ojbect='';
            },
            closeDialog(){
                this.dialogFormVisible=false
            },
            closeDialog1(){
                this.dialogFormVisible1=false
            },
            pushNotice(id,ojbect){
                //消息推送
                if(id=="1"){
                    this.needCheck(id,ojbect)
                }else if(id=="2"){
                    //校内导师该评价了
                    this.teaAssess(id,ojbect)
                }else if(id=="3"){
                    //给出见习成绩
                    this.giveMark(id,ojbect)
                }else if(id=="4"){
                    //还未选择导师
                    this.isSelect(id,ojbect)
                }else if(id=="5"){
                    //选了但未被审核
                    this.waitCheck(id,ojbect)
                }else if(id=="6"){
                    //选了且已审核
                    this.haveCheck(id,ojbect)
                }else if(id=="7"){
                    //查看评定
                    this.assessResult(id,ojbect)
                }else if(id=="8"){
                    //查看成绩
                    this.markResult(id,ojbect)
                }else if(id=="9"){
                    //被选为组长了
                    this.asLeader(id,ojbect)
                }else if(id=="10"){
                    //校外导师该评价了
                    this.tutAssess(id,ojbect)
                }
            },
            //检测学生的第一条通知
            isSelect(id,ojbect){
                //判断学生是否选择导师
                let that = this
                that.$http.post('/yii/notice/push/isselect',{
                    id:id,
                    object:ojbect
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message="success"){
                        alert("推送成功!")
                    }
                })
            },
            //检测学生的第二条通知
            waitCheck(id,ojbect){
                //判断学生是否选择了导师，但是还没有得到审核
                let that = this
                that.$http.post('/yii/notice/push/waitcheck',{
                    id:id,
                    object:ojbect
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message="success"){
                        alert("推送成功!")
                    }
                })
            },
            //检测校内教师的第一条通知
            needCheck(id,ojbect){
                //判断校外老师是否还没有审核
                let that = this
                that.$http.post('/yii/notice/push/needcheck',{
                    id:id,
                    object:ojbect
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message="success"){
                        alert("推送成功!")
                    }
                })
            },
            //检测校内教师的第二条通知
            teaAssess(id,ojbect){
                //判断校内老师是否还没有审核
                let that = this
                that.$http.post('/yii/notice/push/teacherevaluate',{
                    id:id,
                    object:ojbect
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message="success"){
                        alert("推送成功!")
                    }
                })
            },
            //检测校内教师的第三条通知
            giveMark(id,ojbect){
                //判断校内老师是否还没有给出成绩
                let that = this
                that.$http.post('/yii/notice/push/finalmark',{
                    id:id,
                    object:ojbect
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message="success"){
                        alert("推送成功!")
                    }
                })
            },
            //检测学生的第三条通知
            haveCheck(id,ojbect){
                //判断老师已经通过审核，匹配成功
                let that = this
                that.$http.post('/yii/notice/push/havecheck',{
                    id:id,
                    object:ojbect
                }).then(function(res){
                    console.log(res.data)
                })
            },
            //检测学生的第四条通知
            asLeader(id,ojbect){
                //判断是否被设置为组长
                let that = this
                that.$http.post('/yii/notice/push/asleader',{
                    id:id,
                    object:ojbect
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message="success"){
                        alert("推送成功!")
                    }
                })
            },
            //检测学生的第五条通知
            markResult(id,ojbect){
                //学生查看成绩结果
                let that = this
                that.$http.post('/yii/notice/push/markresult',{
                    id:id,
                    object:ojbect
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message="success"){
                        alert("推送成功!")
                    }
                })
            },
            //检测学生的第六条通知
            assessResult(id,ojbect){
                //学生查看评定结果
                let that = this
                that.$http.post('/yii/notice/push/assessresult',{
                    id:id,
                    object:ojbect
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message="success"){
                        alert("推送成功!")
                    }
                })
            },
            //校外教师的通知
            tutAssess(id,ojbect){
                //判断校外评价是否完成
                let that = this
                that.$http.post('/yii/notice/push/tutorevaluate',{
                    id:id,
                    object:ojbect
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message="success"){
                        alert("推送成功!")
                    }
                })
            },
            pageChange: function (page) { // 分页
                if (this.currentpage != page) {
                    this.currentpage = page
                    console.log(this.currentpage)
                }
                this.getNoticeData(this.currentpage)
            },

            prepage: function (page) { // 上一页
                page--
                if (this.currentpage != page) {
                    this.currentpage = page
                    console.log(this.currentpage)
                }
                this.getNoticeData(this.currentpage)
            },

            nextpage: function (page) { // 下一页
                page++
                if (this.currentpage != page) {
                    this.currentpage = page
                    console.log(this.currentpage)
                }
                this.getNoticeData(this.currentpage)
            }
        },
        created(){
           this.getNoticeData()
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
    background-color: #a5c4e8;
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
.display{
  width: 100%;
  height:800px;
  background-color:#edecec;
  position:relative;
  text-align: center;
}
.notice{
  width:90%;
  height:80%;
  display: inline-block;
  margin-top:50px;
  background-color: white;
}
.meeting{
  float:left;
  margin:17px 0 10px 200px;
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
  background-color: #337ab7;
  float: left;
  margin-left: 5px;
  margin-top: 17px;
  margin-bottom: 5px;
}
</style>
