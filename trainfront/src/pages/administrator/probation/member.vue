<template>
  <el-tabs v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="校内教师信息管理" name="first">
      <div class="display1">
        <div class="search">
          <div class="meeting">
            <el-input v-model="inputtea" placeholder="请输入教师姓名" size="mini"></el-input>
          </div>
          <button class="btn3" v-on:click="searchTea()">搜索</button>
          <button class="btn3" type="text" @click="addTea()">添加</button>
<!--          添加教师信息-->
          <el-dialog title="添加一条教师信息" :visible.sync="dialogFormVisible1">
            <el-form :model="teaForm">
              <el-form-item label="用户名" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="teaForm.username" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="姓名" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="teaForm.tName" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="工号" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="teaForm.job_num" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="联系电话" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="teaForm.contactPhone" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="邮箱" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="teaForm.email" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="职称" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="teaForm.rank" auto-complete="off"></el-input>
              </el-form-item>
            </el-form>
            <div slot="footer" style="align-content: center" class="dialog-footer">
              <el-button type="primary" @click="submitTea()">提交</el-button>
              <el-button @click="overDialog1()">取消</el-button>
            </div>
          </el-dialog>
<!--          修改教师信息-->
          <el-dialog title="修改教师信息" :visible.sync="dialogFormVisible">
            <el-form :model="teaForm">
              <el-form-item label="姓名" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="teaForm.tName" auto-complete="off"  :disabled="true"></el-input>
              </el-form-item>
              <el-form-item label="工号" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="teaForm.job_num" auto-complete="off"  :disabled="true"></el-input>
              </el-form-item>
              <el-form-item label="联系电话" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="teaForm.contactPhone" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="邮箱" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="teaForm.email" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="职称" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="teaForm.rank" auto-complete="off"></el-input>
              </el-form-item>
            </el-form>
            <div slot="footer" style="align-content: center" class="dialog-footer">
              <el-button type="primary" @click="saveT()">更新</el-button>
              <el-button class="el-button" @click="resetTea()">重置</el-button>
              <el-button @click="closeDialog1()">取消</el-button>
            </div>
          </el-dialog>
          <button type="button" class="btn3 " id="import-table" v-on:click="uploadFile()">批量导入</button>
          <input id="imFile" style="display: none" type="file" @change="importfxx(this,1)"
                 accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
          <button type="button" class="btn3" id="import-table1" v-on:click="exportFile()">批量导出</button>
        </div>
        <table id="teacherStastics">
          <tr>
            <th>序号</th>
            <th>用户名</th>
            <th>教师姓名</th>
            <th>工号</th>
            <th>联系电话</th>
            <th>邮箱</th>
            <th>职称</th>
            <th>状态</th>
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
            <td v-if='item.status=="1"'>有效</td>
            <td v-if='item.status=="0"'>无效</td>
            <td>
              <span style="cursor: pointer" @click="getEdittea(item.username)"><el-button type="primary" icon="el-icon-edit" circle></el-button></span>
              <span style="cursor: pointer" @click="deleteTea(item.username)"><el-button type="danger" icon="el-icon-delete" circle></el-button></span>
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
    </el-tab-pane>
    <el-tab-pane label="学生信息管理" name="second">
      <div class="display1">
        <div class="search">
          <div class="meeting">
            <el-input v-model="inputstu" placeholder="请输入学生姓名" size="mini"></el-input>
          </div>
          <button class="btn3" v-on:click="searchStu()">搜索</button>
          <button class="btn3" type="text" @click="addStu()">添加</button>
<!--          添加学生信息-->
          <el-dialog title="添加一条学生信息" :visible.sync="dialogFormVisible3">
            <el-form :model="stuForm">
              <el-form-item label="用户名" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.username" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="姓名" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.sName" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="学号" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.sno" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="性别" :label-width="formLabelWidth">
                <select v-model="stuForm.sex" style="font-size:15px;width:180px;" >
                  <option value="1">男</option>
                  <option value="2">女</option>
                </select>
              </el-form-item>
              <el-form-item label="班级代号" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.cId" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="班级名称" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.className" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="专业代号" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.majorId" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="专业名称" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.majorName" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="出生日期" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.bornDate" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="联系方式" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.phone" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="邮箱" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.email" auto-complete="off"></el-input>
              </el-form-item>
            </el-form>
            <div slot="footer" style="align-content: center" class="dialog-footer">
              <el-button type="primary" @click="submitStu()">提交</el-button>
              <el-button @click="overDialog2()">取消</el-button>
            </div>
          </el-dialog>
<!--          修改学生信息-->
          <el-dialog title="修改学生信息" :visible.sync="dialogFormVisible2">
            <el-form :model="stuForm">
              <el-form-item label="姓名" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.sName" auto-complete="off"  :disabled="true"></el-input>
              </el-form-item>
              <el-form-item label="学号" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.sno" auto-complete="off"  :disabled="true"></el-input>
              </el-form-item>
              <el-form-item label="性别" :label-width="formLabelWidth">
                <select v-model="stuForm.sex" style="font-size:15px;width:180px;" >
                  <option value="1">男</option>
                  <option value="2">女</option>
                </select>
              </el-form-item>
              <el-form-item label="班级代号" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.cId" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="班级名称" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.className" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="专业代号" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.majorId" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="专业名称" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.majorName" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="出生日期" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.bornDate" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="联系方式" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.phone" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="邮箱" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="stuForm.email" auto-complete="off"></el-input>
              </el-form-item>
            </el-form>
            <div slot="footer" style="align-content: center" class="dialog-footer">
              <el-button type="primary" @click="saveS()">更新</el-button>
              <el-button class="el-button" @click="resetStu()">重置</el-button>
              <el-button @click="closeDialog2()">取消</el-button>
            </div>
          </el-dialog>
          <button type="button" class="btn3 " id="import-table" v-on:click="uploadFile1()">批量导入</button>
          <input id="imFile1" style="display: none" type="file" @change="importfxx(this,2)"
                 accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
          <button type="button" class="btn3" id="import-table1" v-on:click="exportFile1()">批量导出</button>
        </div>
        <table id="studentStastics">
          <tr>
            <th>序号</th>
            <th>用户名</th>
            <th>姓名</th>
            <th>学号</th>
            <th>性别</th>
            <th>班级代号</th>
            <th>班级名称</th>
            <th>专业代号</th>
            <th>专业名称</th>
            <th>出生日期</th>
            <th>联系方式</th>
            <th>邮箱</th>
            <th>状态</th>
            <th>操作</th>
          </tr>
          <tr v-for="(item,index) in studentList">
            <td>{{(currentpage-1)*8+index+1}}</td>
            <td>{{item.username}}</td>
            <td>{{item.sName}}</td>
            <td>{{item.sno}}</td>
            <td v-if="item.sex==1">男</td>
            <td v-if="item.sex==2">女</td>
            <td>{{item.cId}}</td>
            <td>{{item.className}}</td>
            <td>{{item.majorId}}</td>
            <td>{{item.majorName}}</td>
            <td>{{item.bornDate}}</td>
            <td>{{item.phone}}</td>
            <td>{{item.email}}</td>
            <td v-if='item.status=="1"'>有效</td>
            <td v-if='item.status=="0"'>无效</td>
            <td>
              <span style="cursor: pointer" @click="getEditstu(item.username)"><el-button type="primary" icon="el-icon-edit" circle></el-button></span>
              <span style="cursor: pointer" @click="deleteStu(item.username)"><el-button type="danger" icon="el-icon-delete" circle></el-button></span>
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
    </el-tab-pane>
    <el-tab-pane label="校外教师信息管理" name="third">
      <div class="display1">
        <div class="search">
          <div class="meeting">
            <el-input v-model="inputtut" placeholder="请输入教师姓名" size="mini"></el-input>
          </div>
          <button class="btn3" v-on:click="searchTut()">搜索</button>
          <button class="btn3" type="text" @click="addTut()">添加</button>
<!--          添加校外信息-->
          <el-dialog title="添加一条教师信息" :visible.sync="dialogFormVisible5">
            <el-form :model="tutForm">
              <el-form-item label="用户名" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="tutForm.username" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="姓名" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="tutForm.tName" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="工号" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="tutForm.job_num" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="所在学校" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="tutForm.school_name" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="联系电话" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="tutForm.contactPhone" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="邮箱" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="tutForm.email" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="职称" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="tutForm.rank" auto-complete="off"></el-input>
              </el-form-item>
            </el-form>
            <div slot="footer" style="align-content: center" class="dialog-footer">
              <el-button type="primary" @click="submitTut()">提交</el-button>
              <el-button @click="overDialog3()">取消</el-button>
            </div>
          </el-dialog>
<!--          修改校外信息-->
          <el-dialog title="修改教师信息" :visible.sync="dialogFormVisible4">
            <el-form :model="tutForm">
              <el-form-item label="姓名" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="tutForm.tName" auto-complete="off"  :disabled="true"></el-input>
              </el-form-item>
              <el-form-item label="工号" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="tutForm.job_num" auto-complete="off"  :disabled="true"></el-input>
              </el-form-item>
              <el-form-item label="所在学校" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="tutForm.school_name" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="联系电话" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="tutForm.contactPhone" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="邮箱" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="tutForm.email" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="职称" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="tutForm.rank" auto-complete="off"></el-input>
              </el-form-item>
            </el-form>
            <div slot="footer" style="align-content: center" class="dialog-footer">
              <el-button type="primary" @click="saveTut()">更新</el-button>
              <el-button class="el-button" @click="resetTut()">重置</el-button>
              <el-button @click="closeDialog3()">取消</el-button>
            </div>
          </el-dialog>
          <button type="button" class="btn3 " id="import-table" v-on:click="uploadFile2()">批量导入</button>
          <input id="imFile2" style="display: none" type="file" @change="importfxx(this,3)"
                 accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
          <button type="button" class="btn3" id="import-table1" v-on:click="exportFile2()">批量导出</button>
        </div>
        <table id="tutorStastics">
          <tr>
            <th>序号</th>
            <th>用户名</th>
            <th>教师姓名</th>
            <th>工号</th>
            <th>所属学校</th>
            <th>联系电话</th>
            <th>邮箱</th>
            <th>职称</th>
            <th>状态</th>
            <th>操作</th>
          </tr>
          <tr v-for="(item,index) in tutorList">
            <td>{{(currentpage-1)*8+index+1}}</td>
            <td>{{item.username}}</td>
            <td>{{item.tName}}</td>
            <td>{{item.job_num}}</td>
            <td>{{item.school_name}}</td>
            <td>{{item.contactPhone}}</td>
            <td>{{item.email}}</td>
            <td>{{item.rank}}</td>
            <td v-if='item.status=="1"'>有效</td>
            <td v-if='item.status=="0"'>无效</td>
            <td>
              <span style="cursor: pointer" @click="getEdittut(item.username)"><el-button type="primary" icon="el-icon-edit" circle></el-button></span>
              <span style="cursor: pointer" @click="deleteTut(item.username)"><el-button type="danger" icon="el-icon-delete" circle></el-button></span>
            </td>
          </tr>
        </table>
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
  </el-tabs>
</template>

<script>
    export default {
        name: "member",
        data(){
            return{
                activeName:'first',
                inputtea:'',//教师姓名
                inputstu:'',//学生姓名
                inputtut:'',//教师姓名
                teacherList:[],//用于存放教师信息
                studentList:[],//用户存放学生信息
                tutorList:[],//用于存放校外教师信息
                currentpage: 1, // 当前页
                visiblepage: 10, // 可见页数
                totalpage: '', // 总页数
                dialogFormVisible:false,//校内教师改
                dialogFormVisible1:false,//校内教师增
                dialogFormVisible2:false,//学生改
                dialogFormVisible3:false,//学生增
                dialogFormVisible4:false,//校外教师改
                dialogFormVisible5:false,//校外教师增
                formLabelWidth: '120px',
                teaForm:{
                    username:'',
                    tName:'',
                    job_num:'',
                    contactPhone:'',
                    email:'',
                    rank:''
                },
                tutForm:{
                    username:'',
                    tName:'',
                    school_name:'',
                    job_num:'',
                    contactPhone:'',
                    email:'',
                    rank:''
                },
                stuForm:{
                    username:'',
                    sName:'',
                    sno:'',
                    sex:'',
                    cId:'',
                    className:'',
                    majorId:'',
                    majorName:'',
                    bornDate:'',
                    phone:'',
                    email:''
                }
            }
        },
        methods:{
            handleClick(tab,event){
                console.log(tab,event)
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
            getTutData(){
                //获取校外教师信息
                let that = this
                that.$http.post('/yii/probation/probation/gettut',{page:that.currentpage})
                    .then((res)=>{
                        console.log(res.data)
                        that.tutorList=res.data.data[0]
                        that.totalpage=res.data.data[1]
                        console.log(that.currentpage)
                    })
            },
            getStuData(){
                //获取学生数据
                let that = this
                that.$http.post('/yii/probation/probation/getstu',{page:that.currentpage})
                    .then((res)=>{
                        console.log(res.data)
                        that.studentList=res.data.data[0]
                        that.totalpage=res.data.data[1]
                        console.log(that.currentpage)
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
            searchStu(){
                //搜索学生信息
                let that = this
                that.$http.post('/yii/probation/probation/querystu',{
                    page:that.currentpage,
                    name:that.inputstu
                }).then((res)=>{
                    console.log(res.data)
                    that.studentList=res.data.data[0]
                    that.totalpage=res.data.data[1]
                }).catch((err)=>{
                    console.log(err)
                })
            },
            searchTut(){
                //搜索校外教师信息
                let that = this
                that.$http.post('/yii/probation/probation/querytut',{
                    page:that.currentpage,
                    tName:that.inputtut
                }).then((res)=>{
                    console.log(res.data)
                    that.tutorList=res.data.data[0]
                    that.totalpage =res.data.data[1]
                }).catch((err)=>{
                    console.log(err)
                })
            },
            getEdittea(uName){
                //修改教师信息
                console.log(uName)
                let that =this
                that.$http.post('/yii/probation/probation/edittea',{username:uName})
                    .then((res)=>{
                        console.log(res.data)
                        that.teaForm.username=res.data.data.username
                        that.teaForm.tName=res.data.data.tName
                        that.teaForm.job_num=res.data.data.job_num
                        that.teaForm.contactPhone=res.data.data.contactPhone
                        that.teaForm.email=res.data.data.email
                        that.teaForm.rank=res.data.data.rank
                        that.dialogFormVisible=true
                    })
            },
            getEdittut(uName){
                //修改校外教师信息
                console.log(uName)
                let that =this
                that.$http.post('/yii/probation/probation/edittut',{username:uName})
                    .then((res)=>{
                        console.log(res.data)
                        that.tutForm.username=res.data.data.username
                        that.tutForm.tName=res.data.data.tName
                        that.tutForm.job_num=res.data.data.job_num
                        that.tutForm.school_name=res.data.data.school_name
                        that.tutForm.contactPhone=res.data.data.contactPhone
                        that.tutForm.email=res.data.data.email
                        that.tutForm.rank=res.data.data.rank
                        that.dialogFormVisible4=true
                    })
            },
            getEditstu(uName){
                //修改学生信息
                let that = this
                that.$http.post('/yii/probation/probation/editstu',{username:uName})
                    .then((res)=>{
                        console.log(res.data)
                        that.stuForm.username=res.data.data.username
                        that.stuForm.sName=res.data.data.sName
                        that.stuForm.sno=res.data.data.sno
                        that.stuForm.sex=res.data.data.sex
                        that.stuForm.cId=res.data.data.cId
                        that.stuForm.className=res.data.data.className
                        that.stuForm.majorId=res.data.data.majorId
                        that.stuForm.majorName=res.data.data.majorName
                        that.stuForm.bornDate=res.data.data.bornDate
                        that.stuForm.phone=res.data.data.phone
                        that.stuForm.email=res.data.data.email
                        that.dialogFormVisible2=true
                    })
            },
            deleteTea(uName){
                this.$confirm('此操作将永久删除该教师用户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    that.$http.post('/yii/probation/probation/deletetea',{username:uName})
                        .then((res)=>{
                            console.log(res.data)
                            if(res.data.message=="success"){
                                that.getTeaData()
                                alert("删除该教师用户成功！")
                            }
                        })
                }).catch((err)=>{
                    console.log(err)
                })
            },
            deleteTut(uName){
                this.$confirm('此操作将永久删除该教师用户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    that.$http.post('/yii/probation/probation/deletetut',{username:uName})
                        .then((res)=>{
                            console.log(res.data)
                            if(res.data.message=="success"){
                                that.getTutData()
                                alert("删除该教师用户成功！")
                            }
                        })
                }).catch((err)=>{
                    console.log(err)
                })
            },
            deleteStu(uName){
                this.$confirm('此操作将永久删除该学生用户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    that.$http.post('/yii/probation/probation/deletestu',{username:uName})
                        .then((res)=>{
                            console.log(res.data)
                            if(res.data.message=="success"){
                                that.getStuData()
                                alert("删除该学生用户成功！")
                            }
                        })
                }).catch((err)=>{
                    console.log(err)
                })
            },
            saveT(){
                //保留教师修改信息
                let that = this
                that.$http.post('/yii/probation/probation/altertea',{
                    username:that.teaForm.username,
                    phone:that.teaForm.contactPhone,
                    email:that.teaForm.email,
                    rank:that.teaForm.rank
                }).then((res)=>{
                    console.log(res.data)
                    if (res.data.message=="success"){
                        alert('信息修改成功')
                    }else if(res.data.message=="failure"){
                        alert('信息修改失败')
                    }
                    that.getTeaData()
                    that.dialogFormVisible=false
                }).catch((err)=>{
                    console.log(err)
                })
            },
            saveTut(){
                //保留校外教师修改信息
                let that = this
                that.$http.post('/yii/probation/probation/altertut',{
                    username:that.tutForm.username,
                    tname:that.tutForm.tName,
                    jnum:that.tutForm.job_num,
                    school_name:that.tutForm.school_name,
                    phone:that.tutForm.contactPhone,
                    email:that.tutForm.email,
                    rank:that.tutForm.rank
                }).then((res)=>{
                    console.log(res.data)
                    if (res.data.message=="success"){
                        alert('信息修改成功')
                    }else if(res.data.message=="failure"){
                        alert('信息修改失败')
                    }
                    that.getTutData()
                    that.dialogFormVisible4=false
                }).catch((err)=>{
                    console.log(err)
                })
            },
            saveS(){
                //保留学生修改信息
                let that = this
                that.$http.post('/yii/probation/probation/alterstu',{
                    username:that.stuForm.username,
                    name:that.stuForm.sName,
                    sno:that.stuForm.sno,
                    sex:that.stuForm.sex,
                    cId:that.stuForm.cId,
                    cName:that.stuForm.className,
                    mId:that.stuForm.majorId,
                    mName:that.stuForm.majorName,
                    bornDate:that.stuForm.bornDate,
                    phone:that.stuForm.phone,
                    email:that.stuForm.email
                }).then((res)=>{
                    console.log(res.data)
                    if (res.data.message=="success"){
                        alert('信息修改成功')
                    }else if(res.data.message=="failure"){
                        alert('信息修改失败')
                    }
                    that.getStuData()
                    that.dialogFormVisible2=false
                }).catch((err)=>{
                    console.log(err)
                })
            },
            addTea(){
                //添加教师信息
                this.dialogFormVisible1=true;
                this.teaForm.username='';
                this.teaForm.tName='';
                this.teaForm.job_num='';
                this.teaForm.contactPhone='';
                this.teaForm.email='';
                this.teaForm.rank='';
            },
            addTut(){
                //添加校外教师信息
                this.dialogFormVisible5=true;
                this.tutForm.username='';
                this.tutForm.tName='';
                this.tutForm.school_name='';
                this.tutForm.job_num='';
                this.tutForm.contactPhone='';
                this.tutForm.email='';
                this.tutForm.rank='';
            },
            addStu(){
                //添加学生信息
                this.dialogFormVisible3=true;
                this.stuForm.username='';
                this.stuForm.sName='';
                this.stuForm.sno='';
                this.stuForm.sex='';
                this.stuForm.cId='';
                this.stuForm.className='';
                this.stuForm.majorId='';
                this.stuForm.majorName='';
                this.stuForm.bornDate='';
                this.stuForm.phone='';
                this.stuForm.email='';
            },
            submitTea(){
                //提交新增的教师信息
                this.$confirm('此操作所添加用户的密码默认为123456,是否继续操作?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    if(that.teaForm.username!=''&&that.teaForm.tName!=''&&that.teaForm.job_num!=''&&that.teaForm.contactPhone!=''&&that.teaForm.email!=''&&that.teaForm.rank!='') {
                        that.$http.post('/yii/probation/probation/inserttea', {
                            username: that.teaForm.username,
                            name: that.teaForm.tName,
                            jno: that.teaForm.job_num,
                            phone: that.teaForm.contactPhone,
                            email: that.teaForm.email,
                            rank: that.teaForm.rank
                        }).then((res) => {
                            console.log(res.data)
                            if (res.data.message = "success") {
                                alert("该条教师信息添加成功！")
                                that.dialogFormVisible1 = false
                                that.getTeaData()
                            } else {
                                alert("该条未成功添加")
                            }
                        })
                    }
                    else{
                        alert('信息不能为空！')
                    }
                }).catch((err)=>{
                    console.log(err)
                })
            },
            submitTut(){
                //提交新增的校外教师信息
                this.$confirm('此操作所添加用户的密码默认为123456,是否继续操作?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    if(that.tutForm.username!=''&&that.tutForm.tName!=''&&that.tutForm.job_num!=''&&that.tutForm.school_name!=''&&that.tutForm.contactPhone!=''&&that.tutForm.email!=''&&that.tutForm.rank!='') {
                        that.$http.post('/yii/probation/probation/inserttut', {
                            username: that.tutForm.username,
                            name: that.tutForm.tName,
                            jno: that.tutForm.job_num,
                            school_name: that.tutForm.school_name,
                            phone: that.tutForm.contactPhone,
                            email: that.tutForm.email,
                            rank: that.tutForm.rank
                        }).then((res) => {
                            console.log(res.data)
                            if (res.data.message = "success") {
                                alert("该条教师信息添加成功！")
                                that.dialogFormVisible5 = false
                                that.getTutData()
                            } else {
                                alert("该条未成功添加")
                            }
                        })
                    }
                    else{
                        alert('信息不能为空！')
                    }
                }).catch((err)=>{
                    console.log(err)
                })
            },
            submitStu(){
                //提交新增的学生信息
                this.$confirm('此操作所添加用户的密码默认为123456,是否继续操作?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    if(that.stuForm.username!=''&&that.stuForm.sName!=''&&that.stuForm.sno!=''&&that.stuForm.sex!=''&&that.stuForm.cId!=''&&that.stuForm.className!=''&&that.stuForm.majorId!=''&&that.stuForm.majorName!=''&&that.stuForm.bornDate!=''&&that.stuForm.phone!=''&&that.stuForm.email!='')
                    {
                        that.$http.post('/yii/probation/probation/insertstu',{
                            username:that.stuForm.username,
                            name:that.stuForm.sName,
                            sno:that.stuForm.sno,
                            sex:that.stuForm.sex,
                            cId:that.stuForm.cId,
                            className:that.stuForm.className,
                            mId:that.stuForm.majorId,
                            majorName: that.stuForm.majorName,
                            bornDate:that.stuForm.bornDate,
                            phone:that.stuForm.phone,
                            email:that.stuForm.email
                        }).then((res)=>{
                            console.log(res.data)
                            if (res.data.message = "success") {
                                alert("该条学生信息添加成功！")
                                that.dialogFormVisible3 = false
                                that.getStuData()
                            } else {
                                alert("该条未成功添加")
                            }
                        })
                    }
                    else{
                        alert("信息不能为空！")
                    }
                })
            },
            resetTea(){
                //重置校内教师信息
                // this.$refs.teaForm.resetFields();
                this.teaForm.tName='';
                this.teaForm.job_num='';
                this.teaForm.contactPhone='';
                this.teaForm.email='';
                this.teaForm.rank='';
            },
            resetTut(){
                //重置校外教师信息
                this.tutForm.tName='';
                this.tutForm.job_num='';
                this.tutForm.school_name='';
                this.tutForm.contactPhone='';
                this.tutForm.email='';
                this.tutForm.rank='';
            },
            resetStu(){
                this.stuForm.sName='';
                this.stuForm.sno='';
                this.stuForm.sex='';
                this.stuForm.cId='';
                this.stuForm.className='';
                this.stuForm.majorId='';
                this.stuForm.majorName='';
                this.stuForm.bornDate='';
                this.stuForm.phone='';
                this.stuForm.email='';
            },
            uploadFile(){
                alert('此操作所添加用户的密码默认为123456！')
                this.imFile.click()
            },
            uploadFile1(){
                alert('此操作所添加用户的密码默认为123456！')
                this.imFile1.click()
            },
            uploadFile2(){
                alert('此操作所添加用户的密码默认为123456！')
                this.imFile2.click()
            },
            formatJson (filterVal, jsonData) {
                //数据格式化
                return jsonData.map(v => filterVal.map(j => v[j]))
            },
            exportFile(){
                //校内批量导出
                require.ensure([], () => {
                    const {export_json_to_excel} = require("../../../excel/Export2Excel.js");//引入文件
                    // excel表中要显示的标题头
                    const tHeader = ['序号','用户名','教师姓名','工号','联系电话','邮箱','职称'];
                    // 导出的表头字段名
                    const filterVal =['tId','username','tName','job_num','contactPhone','email','rank']
                    const list = this.teacherList
                    const data = this.formatJson(filterVal, list);
                    export_json_to_excel(tHeader, data, "校内教师信息表");//此处的函数名要与Export2Excel.js中的对应
                })
            },
            exportFile1(){
                //学生批量导出
                require.ensure([], () => {
                    const {export_json_to_excel} = require("../../../excel/Export2Excel.js");//引入文件
                    // excel表中要显示的标题头
                    const tHeader = ['序号','用户名','学生姓名','学号','性别','班级代号','班级名称','专业代号','专业名称','出生日期','联系方式','邮箱'];
                    // 导出的表头字段名
                    const filterVal =['sId','username','sName','sno','sex','cId','className','majorId','majorName','bornDate','phone','email']
                    const list = this.studentList
                    const data = this.formatJson(filterVal, list);
                    export_json_to_excel(tHeader, data, "学生信息表");//此处的函数名要与Export2Excel.js中的对应
                })
            },
            exportFile2(){
                //校外教师批量导出
                require.ensure([], () => {
                    const {export_json_to_excel} = require("../../../excel/Export2Excel.js");//引入文件
                    // excel表中要显示的标题头
                    const tHeader = ['序号','用户名','教师姓名','工号','所属学校','联系电话','邮箱','职称'];
                    // 导出的表头字段名
                    const filterVal =['tId','username','tName','job_num','school_name','contactPhone','email','rank']
                    const list = this.tutorList
                    const data = this.formatJson(filterVal, list);
                    export_json_to_excel(tHeader, data, "校外教师信息表");//此处的函数名要与Export2Excel.js中的对应
                })
            },
            importfxx(obj,flag){
                let _this = this
                let inputDOM = this.$refs.inputer   // 通过DOM取文件数据
                this.file = event.currentTarget.files[0]
                var rABS = false // 是否将文件读取为二进制字符串
                var f = this.file
                var reader = new FileReader()
                FileReader.prototype.readAsBinaryString = function (f){
                    var binary = ''
                    var rABS = false // 是否将文件读取为二进制字符串
                    var pt = this
                    var wb // 读取完成的数据
                    var outdata
                    var reader = new FileReader()
                    reader.onload = function (e) {
                        var bytes = new Uint8Array(reader.result)
                        var length = bytes.byteLength
                        for (var i = 0; i < length; i++) {
                            binary += String.fromCharCode(bytes[i])
                        }
                        var XLSX = require('xlsx')
                        if (rABS) {
                            wb = XLSX.read(btoa(fixdata(binary)), { // 手动转化
                                type: 'base64'
                            })
                        } else {
                            wb = XLSX.read(binary, {
                                type: 'binary'
                            })
                        }
                        // outdata就是你想要的东西 excel导入的数据
                        outdata = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]])
                        // excel 数据再处理
                        let arr = []
                        console.log(flag)
                        if (flag == 1) {
                            outdata.map(v => {
                                //批量导入校内导师
                                let obj = {}
                                obj.username = v.用户名
                                obj.tName = v.姓名
                                obj.job_num = v.工号
                                obj.contactPhone = v.联系方式
                                obj.email = v.邮箱
                                obj.rank = v.职称
                                arr.push(obj)
                            })
                        } else if (flag == 2) {
                            //批量导入学生
                            outdata.map(v => {
                                let obj = {}
                                obj.username = v.用户名
                                obj.sName = v.姓名
                                obj.sno = v.学号
                                obj.sex = v.性别
                                obj.cId = v.班级代号
                                obj.className = v.班级名称
                                obj.majorId = v.专业代号
                                obj.majorName = v.专业名称
                                obj.bornDate = v.出生日期
                                obj.phone = v.联系方式
                                obj.email = v.邮箱
                                arr.push(obj)
                            })
                        } else if (flag == 3) {
                            //批量导入校外教师
                            outdata.map(v => {
                                let obj = {}
                                obj.username = v.用户名
                                obj.tName = v.姓名
                                obj.job_num = v.工号
                                obj.school_name = v.所在学校
                                obj.contactPhone = v.联系方式
                                obj.email = v.邮箱
                                obj.rank = v.职称
                                arr.push(obj)
                            })
                        }
                        _this.memberList = [...arr]
                        let data = {
                            data: JSON.stringify(_this.memberList)
                        }
                        if (flag == 1) {
                            _this.$http.post('/yii/probation/probation/importexcel', data).then(body => {
                                console.log(body)
                                alert('导入成功')
                                _this.getTeaData()
                            })
                        } else if (flag == 2) {
                            _this.$http.post('/yii/probation/probation/importexcel1', data).then(body => {
                                alert('导入成功')
                                _this.getStuData()
                            })
                        } else if (flag == 3) {
                            _this.$http.post('/yii/probation/probation/importexcel2', data).then(body => {
                                alert('导入成功')
                                _this.getTutData()
                            })
                        }
                    }
                    reader.readAsArrayBuffer(f)
                  }
                if (rABS) {
                    reader.readAsArrayBuffer(f)
                } else {
                    reader.readAsBinaryString(f)
                }
            },
            closeDialog1(){
                this.dialogFormVisible=false
            },
            closeDialog2(){
                this.dialogFormVisible2=false
            },
            closeDialog3(){
                this.dialogFormVisible4=false
            },
            overDialog1(){
                this.dialogFormVisible1=false
            },
            overDialog2(){
                this.dialogFormVisible3=false
            },
            overDialog3(){
                this.dialogFormVisible5=false
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
            this.getTeaData()
            this.getStuData()
            this.getTutData()
        },
        mounted(){
            this.imFile = document.getElementById('imFile')
            this.imFile1 = document.getElementById('imFile1')
            this.imFile2 = document.getElementById('imFile2')
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
    margin:14px 0 10px 0px;
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
