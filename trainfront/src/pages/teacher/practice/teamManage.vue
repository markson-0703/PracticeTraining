<template>
  <el-tabs v-model="activeName" type="card" @tab-click="getValue" style="padding-top: 50px">
    <el-tab-pane :key="index" v-for="(item,index) in arrangeList" :label="item.site" :name="index+1+''">
      <table id="arrangeStastics">
        <tr>
          <th>序号</th>
          <th>姓名</th>
          <th>学号</th>
          <th>见习学校</th>
          <th>校外导师</th>
          <th>见习年级</th>
          <th>设置组长</th>
          <th>状态</th>
          <th>操作</th>
        </tr>
        <tr v-for="(member,index) in studentList">
          <td>{{(currentpage-1)*8+index+1}}</td>
          <td>{{member.sName}}</td>
          <td>{{member.sno}}</td>
          <td>{{member.school_name}}</td>
          <td>{{member.tutor_name}}</td>
          <td>
            <el-select size="small" placeholder="请选择年级" v-model="gradeValue[index]" @change="getGrade($event)">
              <el-option v-for="option in gradeList " :key="option.id" :label="option.title" :value="option.id"></el-option>
            </el-select>
            <span><el-tag v-if="member.grade!=null">{{member.grade}}</el-tag></span>
          </td>
          <td>
            <el-button :plain="true" @click="setLeader(member.sno)" v-if="(member.leader==0)">设置</el-button>
            <el-button :plain="true" @click="removeLeader(member.sno)" v-show="isLeader||(member.leader==1)">取消</el-button>
          </td>
          <td>
            <span style="cursor: pointer" @click="checkStatus(member.sno)">
              <el-button icon="el-icon-bell" circle style="background-color:lightblue;border:0px"></el-button>
            </span>
            <span>
              <el-progress :text-inside="true" :stroke-width="22" :percentage="0" status="exception" style="color:black" v-if="(status1)&&(member.sno==checkSno)"></el-progress>
            </span>
            <span>
              <el-progress :text-inside="true" :stroke-width="22" :percentage="50" status="warning" v-if="(status2)&&(member.sno==checkSno)"></el-progress>
            </span>
            <span>
              <el-progress :text-inside="true" :stroke-width="24" :percentage="100" status="success" v-if="(status3)&&(member.sno==checkSno)"></el-progress>
            </span>
          </td>
          <td>
            <span style="cursor: pointer" @click="agree(member.sno)"><el-button type="success" v-show="member.ischecked==0" size="small">同意</el-button></span>
            <span style="cursor: pointer"><el-button type="info" v-show="member.ischecked==1" size="small">已审核</el-button></span>
            <span style="cursor: pointer" @click="del(member.sno)"> <el-button type="danger" v-show="havecheck||(member.ischecked==1)" size="small">删除</el-button></span>
          </td>
        </tr>
      </table>
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
    </el-tab-pane>
  </el-tabs>
</template>

<script>
    export default {
        name: "teamManage",
        data(){
            return{
                activeName: '1',
                username:'',
                arrangeList:[],
                studentList:[],
                currentpage: '1',
                totalpage: '',
                visiblepage: 10,
                finalGrade:'小学',//学生最终选中的年级，默认设置为小学
                site:'',//当前选中的实习点
                key:0,//下拉框索引
                isLeader:false,
                status1:false,
                status2:false,
                status3:false,
                havecheck:false,
                gradeList:[
                    {
                        id:'1',
                        title:'小学'
                    },
                    {
                        id:'2',
                        title:'初中'

                    },
                    {
                        id:'3',
                        title:'高中'

                    }
                ],
                gradeValue:['小学','初中','高中'],
                currentTime:'',//当前时间
                checkSno:''
            }
        },
        methods:{
            format(percentage) {
                return percentage === 100 ? '满' : `${percentage}%`;
            },
            checkStatus(sno){
                //进程状态判断,时间比较
                console.log(sno)
                let that = this
                that.$http.post('/yii/practice/message/getstatus',{
                    sno:sno
                }).then((res)=>{
                    console.log(res.data)
                    that.currentTime=that.format(new Date(), "yyyy-MM-dd");
                    that.checkSno=res.data.data.sno
                    console.log(that.currentTime)
                    var start=res.data.data.startTime
                    var end=res.data.data.endTime
                    if(start&&end) {
                        if ((that.currentTime > start) && (that.currentTime < end)) {
                            that.status2 = true
                            that.status1 = false
                            that.status3 = false
                        } else if (that.currentTime > end) {
                            that.status3 = true
                            that.status1 = false
                            that.status2 = false
                        }
                    }else{
                        that.status1=true
                        that.status2 = false
                        that.status3 = false
                    }
                })
            },
            format(date, fmt) {
                let o = {
                    "M+": date.getMonth() + 1, //月份
                    "d+": date.getDate(), //日
                    "H+": date.getHours(), //小时
                    "m+": date.getMinutes(), //分
                    "s+": date.getSeconds(), //秒
                    "q+": Math.floor((date.getMonth() + 3) / 3), //季度
                    "S": date.getMilliseconds() //毫秒
                };
                if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (date.getFullYear() + "").substr(4 - RegExp.$1.length));
                for (let k in o)
                    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
                return fmt;
            },
            getValue(item){
                this.site = item.label;
                this.getArrangeInfo()
            },
            getGrade(id){
                //获取下拉框的值
                let obj = {};
                obj = this.gradeList.find((option)=>{//这里的gradeList就是上面遍历的数据源
                    return option.id === id;//筛选出匹配数据
                });
                console.log(obj.title);//获取的 title
                console.log(id);//获取的 id
                this.key=id;
                this.finalGrade=obj.title
            },
            setLeader(sno){
                //设置为组长
                let that = this
                that.$http.post('/yii/practice/examine/setleader',{
                    sno:sno,
                }).then((res)=>{
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.StudentInfo()
                        alert("成功将该名学生设置为组长！")
                    }else{
                        alert("设置组长失败！")
                    }
                })
            },
            removeLeader(sno){
                this.$confirm('是否要移除该用户的组长身份?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=> {
                    let that = this
                    that.$http.post('/yii/practice/examine/removeleader', {
                        sno: sno,
                    }).then((res) => {
                        console.log(res.data)
                        if(res.data.message=="success"){
                            alert("成功移除组长身份！")
                            that.StudentInfo()
                        }else{
                            alert("操作失败！")
                        }
                    })
                })
            },
            agree(sno){
                //导师审核
                let that = this
                that.$http.post('/yii/practice/examine/checkstu',{
                    sno:sno,
                    grade:this.finalGrade
                }).then((res)=>{
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.StudentInfo()
                        alert("成功审核！")
                    }else{
                        alert("操作失败！")
                    }
                })
            },
            del(sno){
                //学生经过了审核，且当某学生实习结束之后导师考虑是否删除
                this.$confirm('是否要删除该学生的信息?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    that.$http.post('/yii/probation/verify/deleteset',{
                        sno:sno
                    }).then((res)=>{
                        console.log(res.data)
                        if(res.data.message=="success"){
                            //重新获取数据
                            that.StudentInfo()
                            alert("学生信息删除成功！")
                        }else{
                            alert("学生信息删除失败！")
                        }
                    }).catch((err)=>{
                        console.log(err)
                    })
                })
            },
            getArrangeInfo(){
                //获取分配信息，一共多少实习点
                let that = this
                that.$http.post('/yii/practice/message/getproass',{
                    username:that.username
                }).then((res)=>{
                    console.log(res.data)
                    that.arrangeList=res.data.data
                    if(that.activeName=="1"){
                        that.site=that.arrangeList[0].site
                        console.log(that.site)
                    }
                    that.StudentInfo()
                })
            },
            StudentInfo(){
                //获取该教师各个实习点的学生列表
                let that = this
                console.log(that.site)
                that.$http.post('/yii/practice/message/getsitestu',{
                    site:that.site,
                    page: that.currentpage,
                    username: that.username
                }).then((res)=>{
                    console.log(res.data)
                    that.studentList=res.data.data[0]
                    that.totalpage=res.data.data[1]
                })
            },
            pageChange: function (page) { // 分页
                if (this.currentpage != page) {
                    this.currentpage = page
                    console.log(this.currentpage)
                }
                this.StudentInfo(this.currentpage)
            },

            prepage: function (page) { // 上一页
                page--
                if (this.currentpage != page) {
                    this.currentpage = page
                    console.log(this.currentpage)
                }
                this.StudentInfo(this.currentpage)
            },

            nextpage: function (page) { // 下一页
                page++
                if (this.currentpage != page) {
                    this.currentpage = page
                    console.log(this.currentpage)
                }
                this.StudentInfo(this.currentpage)
            }

        },
        created() {
            this.username=this.$store.getters.getsName
            this.getArrangeInfo()
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
