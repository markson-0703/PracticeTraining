<template>
  <div class="display" id="main">
    <el-tabs v-model="activeName" @tab-click="handleClick" style="height: fit-content;">
      <el-tab-pane label="自我评价" name="first">
        <div class="myAssess">
          <div class="assess">
            <el-form ref="assessData" :model="assessData" label-width="120px">
              <el-row
                v-for="(item,index) in assessData.myForm"
                :key="index"
                style="border-bottom: 1px solid #f0f0f0;padding: 10px;"
              >
              <el-form-item label="日期:" style="width:400px;" prop="date">
                <el-date-picker
                  v-model="item.date"
                  type="date"
                  placeholder="选择日期"
                  value-format="yyyy-MM-dd">
                </el-date-picker>
              </el-form-item>
              <el-form-item label="星期:" style="width:400px;" prop="weekday">
              <el-input v-model="item.weekday"></el-input>
             </el-form-item>
              <el-form-item label="节数:" style="width:400px;" prop="section">
                <el-input v-model="item.section"></el-input>
              </el-form-item>
              <el-form-item label="授课班级:" style="width:400px;" prop="grade">
                  <el-input v-model="item.grade"></el-input>
              </el-form-item>
                <el-form-item label="课型:" style="width:400px;" prop="classform">
                  <el-input v-model="item.classform"></el-input>
                </el-form-item>
              <el-form-item label="授课内容:" style="width:400px;" prop="content">
                <el-input type="textarea" v-model="item.content"></el-input>
              </el-form-item>
              <el-form-item label="自我评价:" prop="selfAssess">
                <quill-editor
                  v-model="item.selfAssess"
                  ref="myQuillEditor"
                  :options="custom"
                  @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
                  @change="onEditorChange($event)"
                >
                </quill-editor>
              </el-form-item>
                <el-button type="warning" size="small" plain @click="submitOne(item.date,item.weekday,item.section,item.grade,item.classform,item.content,item.selfAssess)">提交</el-button>
                <el-button type="danger" v-if="assessData.myForm.length > 1" size="small" plain @click="removeRow(index)">删除</el-button>
              </el-row>
              <el-form-item class="actions">
                <el-button size="small" @click="addOne">新增实习授课评价</el-button>
              </el-form-item>
            </el-form>
          </div>
        </div>
      </el-tab-pane>
      <el-tab-pane label="师生评价意见" name="second">
        <div class="info">
        <span><label>姓名:</label>{{this.sName}}</span>
          <span><label>实习学校:</label>{{this.school}}</span>
          <span><label>校内指导老师:</label>{{this.teacher}}</span>
          <span><label>校外指导老师:</label>{{this.tutor}}</span>
        </div>
        <div class="detail">
          <table id="assessStastics">
            <tr>
              <th style="width:3%">序号</th>
              <th style="width:8%">日期</th>
              <th style="width:3%">星期</th>
              <th style="width:3%">节数</th>
              <th style="width:7%">授课班级</th>
              <th style="width:6%">课型</th>
              <th style="width:10%">授课内容</th>
              <th style="width:20%">自我评价</th>
              <th style="width:20%">小组评价</th>
              <th style="width:20%">校外指导教师意见</th>
            </tr>
            <tr v-for="(item,index) in assessList">
              <td>{{index+1}}</td>
              <td>{{item.time}}</td>
              <td>{{item.weekday}}</td>
              <td>{{item.section}}</td>
              <td>{{item.class}}</td>
              <td>{{item.classform}}</td>
              <td>{{item.content}}</td>
              <td>{{item.selfAssess}}</td>
              <td v-if='item.groupAssess==null' style="color:red">暂无小组评价！</td>
              <td  v-if='item.groupAssess!=null'>{{item.groupAssess}}</td>
              <td v-if="item.tutorAssess==null" style="color:red">暂无校外指导教师意见！</td>
              <td v-if="item.tutorAssess!=null">{{item.tutorAssess}}</td>
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
      </el-tab-pane>
      <el-tab-pane label="小组评价" name="third" v-if="isLeader">
        <div class="forLeader">
          <table id="teamStastics">
            <tr>
              <th>序号</th>
              <th>组员</th>
              <th>校内指导老师</th>
              <th>校外指导老师</th>
              <th>评定详情</th>
            </tr>
            <tr v-for="(item,index) in memberList">
              <td>{{index+1}}</td>
              <td>{{item.sName}}</td>
              <td>{{item.tName}}</td>
              <td>{{item.tutor_name}}</td>
              <td>
                <span>
                 <el-button type="primary" plain size="small" @click="assessDetail(item.username)">查看</el-button>
                </span>
              </td>
            </tr>
          </table>
<!--          查看授课评价详情-->
          <el-dialog title="授课评价表" :visible.sync="dialogVisible" :before-close="handleClose" width="800px">
              <table id="perStastics">
                <tr>
                  <th>序号</th>
                  <th>日期</th>
                  <th>星期</th>
                  <th>授课班级</th>
                  <th>课型</th>
                  <th>授课内容</th>
                  <th>评价详情</th>
                </tr>
                <tr v-for="(item,index) in infoList">
                  <td>{{index+1}}</td>
                  <td>{{item.time}}</td>
                  <td>{{item.weekday}}</td>
                  <td>{{item.class}}</td>
                  <td>{{item.classform}}</td>
                  <td>{{item.content}}</td>
                  <td>
                    <span style="cursor: pointer"><el-button type="danger" size="small" plain @click="makeAssess(item.id)">评价表详情</el-button></span>
                  </td>
                </tr>
              </table>
          </el-dialog>
<!--          每一张评价表的具体内容-->
          <el-dialog title="评价表详情" :visible.sync="dialogVisible1" :before-close="handleClose" width="800px">
            <el-form :model="contentDetail">
              <el-form-item label="自我评价" :label-width="formLabelWidth">
                <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="contentDetail.selfContent" :disabled="true"></el-input>
              </el-form-item>
              <el-form-item label="小组评价" :label-width="formLabelWidth">
                <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="contentDetail.groupContent" placeholder="请输入小组评价内容"></el-input>
              </el-form-item>
              <el-form-item label="校外指导教师意见" :label-width="formLabelWidth">
                <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" v-model="contentDetail.tutContent" :disabled="true" placeholder="此处为校外指导老师审阅意见"></el-input>
              </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
           <el-button @click="dialogVisible1= false">取 消</el-button>
           <el-button type="primary" v-show="this.flag" @click="submitGroup(contentDetail.id)">提交</el-button>
           <el-button type="primary" v-show="!this.flag" @click="updateGroup(contentDetail.id)">更新</el-button>
           </span>
          </el-dialog>
        </div>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
    import * as Quill from 'quill'  //引入编辑器
    //quill编辑器的字体
    var fonts = ['SimSun', 'SimHei','Microsoft-YaHei','KaiTi','FangSong','Arial','Times-New-Roman','sans-serif'];
    var Font = Quill.import('formats/font');
    Font.whitelist = fonts; //将字体加入到白名单
    Quill.register(Font, true);
    export default {
        name: "instructAssess",
        data(){
            return{
                activeName:'first',
                formLabelWidth: '120px',
                isLeader:false,//判断是否为小组组长
                currentpage: 1, // 当前页
                visiblepage: 10, // 可见页数
                totalpage: '', // 总页数
                username:'',
                flag:false,
                nowId:'',
                sName:'',
                school:'',
                teacher:'',//校内导师
                tutor:'',//校外导师
                assessList:[],
                infoList:[],//组员的所有评价
                memberList:[],//组员信息
                assessData:{
                    myForm:[{
                        date:'',
                        weekday:'',
                        section:'',
                        grade:'',
                        classform:'',
                        content:'',
                        selfAssess:'',
                        groupAssess:'',
                        tutorAssess:''
                    }]
                },
                contentDetail:{
                    id:'',
                    selfContent:'',//自我评价内容
                    groupContent:'',//小组评价内容
                    tutContent:''//校外教师审阅
                },
                dialogVisible:false,
                dialogVisible1:false,
                custom:{
                    //自定义工具栏
                    theme:'bubble',
                    placeholder: "请在这里输入",
                    modules:{
                        toolbar:[
                            ['bold', 'italic', 'underline', 'strike'],        //加粗，斜体，下划线，删除线
                            [{ 'header': 1 }, { 'header': 2 }],               // 标题，键值对的形式；1、2表示字体大小
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],          //列表
                            [{ 'script': 'sub'}, { 'script': 'super' }],      // 上下标
                            [{ 'indent': '-1'}, { 'indent': '+1' }],          // 缩进
                            [{ 'direction': 'rtl' }],                         // 文本方向
                            [{ 'size': ['small', false, 'large', 'huge'] }],  // 字体大小
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],         //几级标题
                            [{ 'color': [] }, { 'background': [] }],          // 字体颜色，字体背景颜色
                            [{ 'font': [] }],         //字体
                            [{ 'align': [] }],        //对齐方式
                            ['clean'],        //清除字体样式
                            ['upload']
                        ]
                    }
                },
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
            makeAssess(id){
                console.log(id)
                let that= this
                that.$http.post('/yii/evaluation/assess/everyform',{
                    id:id
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        this.contentDetail.id=res.data.data.id
                        that.contentDetail.selfContent=res.data.data.selfAssess
                        that.contentDetail.groupContent=res.data.data.groupAssess
                        that.contentDetail.tutContent=res.data.data.tutorAssess
                        that.dialogVisible1=true
                        if(res.data.data.groupAssess==null){
                            this.flag=true
                        }else{
                            this.flag=false
                        }
                    }else{
                        this.$notify.error({
                            title: '错误',
                            message: '该组员还未完成自评！'
                        });
                    }
                })
            },
            submitGroup(currentId){
                console.log(currentId)
                let that = this
                that.$http.post('/yii/evaluation/assess/teamassess',{
                    id:currentId,
                    content:that.contentDetail.groupContent,
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.dialogVisible1=false
                        this.$message({
                            message: '小组评价成功！',
                            type: 'success'
                        });
                    }else{
                        this.$message.error('小组评价失败！');
                    }
                })
            },
            updateGroup(currentId){
                let that = this
                that.$http.post('/yii/evaluation/assess/assessupdate',{
                    id:currentId,
                    content:that.contentDetail.groupContent,
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.dialogVisible1=false
                        this.$message({
                            message: '小组评价已更新!',
                            type: 'success'
                        });
                    }
                })
            },
            addOne(){
                this.assessData.myForm.push({
                    date:'',
                    weekday:'',
                    section:'',
                    grade:'',
                    classform:'',
                    content:'',
                    selfAssess:'',
                    groupAssess:'',
                    tutorAssess:''
                })
            },
            removeRow(index){
                this.assessData.myForm.splice(index,1)
            },
            getInfo(uname){
                console.log(uname)
                let that = this
                that.$http.post('/yii/evaluation/assess/seeassess',{
                    username:uname
                }).then(function(res){
                    that.infoList=res.data.data
                    if(res.data.message=='success'){
                        that.dialogVisible=true
                    }
                })
            },
            getBaseinfo(){
               //获取基本信息
               let that = this
               that.$http.post('/yii/evaluation/assess/myinfo',{
                   username:that.username
               }).then(function(res){
                   console.log(res.data)
                   that.sName=res.data.data.sName
                   that.teacher=res.data.data.tName
                   that.tutor=res.data.data.tutor_name
                   that.school=res.data.data.school_name
               })
            },
            getAllsubmit(){
                //获取学生所有实习授课评价
                let that = this
                that.$http.post('/yii/evaluation/assess/getallsubmit',{
                    username:that.username,
                    page:that.currentpage
                }).then(function(res){
                    console.log(res.data)
                    that.assessList=res.data.data[0]
                    that.totalpage=res.data.data[1]
                })
            },
            assessDetail(uName){
                //查看授课评价详情
                let that = this
                that.getInfo(uName)
            },
            myMember(){
                //获取成员信息
                let that = this
                that.$http.post('/yii/evaluation/assess/getmember',{
                   username:that.username
                }).then(function(res){
                    console.log(res.data)
                    that.memberList=res.data.data
                })
            },
            submitOne(date,weekday,section,grade,classform,content,selfAssess){
                //提交一次授课评价情况
                this.$confirm("此操作将保存当前见习信息, 是否继续?", "提示", {
                    confirmButtonText: "确定",
                    cancelButtonText: "取消",
                    type: "warning"
                }).then(()=> {
                    let that = this
                   var newAssess= selfAssess.replace(/<[^>]+>/g, "")
                    that.$http.post('/yii/evaluation/assess/submitoneassess',{
                    username:that.username,
                        date:date,
                        weekday:weekday,
                        section:section,
                        grade:grade,
                        classform:classform,
                        content:content,
                        selfAssess:newAssess
                    }).then(function(res){
                        console.log(res.data)
                        if(res.data.message=="success"){
                            alert('成功提交！')
                            that.getAllsubmit()
                        }else if(res.data.message=="信息已存在"){
                            alert('请勿重复提交！')
                        }else{
                            alert('提交失败！')
                        }
                    })
                }).catch((err)=>{
                    console.log(err)
                })
            },
            onEditorBlur(){//失去焦点事件
            },
            onEditorFocus(){//获得焦点事件
            },
            onEditorChange(){//内容改变事件
            },
            judgeLeader(){
                //判断该名用户是否为小组长
                let that = this
                that.$http.post('/yii/evaluation/assess/judgeleader',{
                    username:that.username
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.data.leader==1){
                        that.isLeader=true
                        this.myMember()
                    }
                })
            },
            pageChange: function (page) { // 分页
                if (this.currentpage != page) {
                    this.currentpage = page
                }
                this.getAllsubmit()
            },
            prepage: function (page) { // 上一页
                page--
                if (this.currentpage != page) {
                    this.currentpage = page
                }
                this.getAllsubmit()
            },

            nextpage: function (page) { // 下一页
                page++
                if (this.currentpage != page) {
                    this.currentpage = page
                }
                this.getAllsubmit()
            }

        },
        created() {
            this.username=this.$store.getters.getsName
            this.judgeLeader()
            this.getBaseinfo()
            this.getAllsubmit()
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
  @import '../../../common/css/font.css';
  @import '../../../common/css/pagination.css';
  @import '../../../common/css/templateTable.css';
  .display{
    text-align: center;
    height:100%;
  }
  .myAssess{
    display: inline-block;
    font-weight: bolder;
  }
  .info span{
    width:100%;
    padding-right: 50px;
  }
  .info label{
    font-weight: bolder;
    color:#ac1941;
  }
</style>
