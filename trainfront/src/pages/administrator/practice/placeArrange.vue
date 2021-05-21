<template>
  <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
    <el-tab-pane label="分配文件上传" name="first">
      <div>
        <h3>请管理员在此处上传教师教学实践地点分配的文档</h3>
      </div>
      <i class="el-icon-upload"></i>
      <div class="upload_text">
        <button type="button" class="btn2 " id="import-table" @click="uploadFile()">上传文件</button>
        <input id="imFile" style="display: none" type="file" @change="importfxx(this)"
               accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
      </div>
    </el-tab-pane>
    <el-tab-pane label="实践地点的分配与调整" name="second">
      <div class="display1">
        <div class="search">
          <div class="meeting">
            <el-input v-model="inputName" placeholder="请输入教师姓名" size="mini"></el-input>
          </div>
          <div class="newbtn">
            <button class="btn3" v-on:click="searchAssign()">搜索</button>
            <button class="btn3" type="text" @click="addOne()">添加</button>
          </div>
<!--          添加一条分配信息-->
          <el-dialog title="添加一条实习分配信息" :visible.sync="dialogFormVisible">
            <el-form :model="assForm">
              <el-form-item label="教师姓名" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="assForm.tName" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="工号" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="assForm.job_num" auto-complete="off"></el-input>
              </el-form-item>
              <el-form-item label="实践类型" :label-width="formLabelWidth">
                <select v-model="assForm.typeId" style="font-size:15px;width:180px;" >
                  <option value="1">见习</option>
                  <option value="2">实习</option>
                </select>
              </el-form-item>
              <el-form-item label="地点" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="assForm.site" auto-complete="off"></el-input>
              </el-form-item>
            </el-form>
            <div slot="footer" style="align-content: center" class="dialog-footer">
              <el-button type="primary" @click="save()">更新</el-button>
              <el-button class="el-button" @click="resetass()">重置</el-button>
              <el-button @click="closeDialog()">取消</el-button>
            </div>
          </el-dialog>
<!--          修改分配信息-->
          <el-dialog title="修改实习分配信息" :visible.sync="dialogFormVisible1">
            <el-form :model="assForm">
              <el-form-item label="教师姓名" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="assForm.tName" auto-complete="off"  :disabled="true"></el-input>
              </el-form-item>
              <el-form-item label="工号" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="assForm.job_num" auto-complete="off"  :disabled="true"></el-input>
              </el-form-item>
              <el-form-item label="实践类型" :label-width="formLabelWidth">
                <select v-model="assForm.typeId" style="font-size:15px;width:180px;" >
                  <option value="1">见习</option>
                  <option value="2">实习</option>
                </select>
              </el-form-item>
              <el-form-item label="地点" :label-width="formLabelWidth">
                <el-input style="width: 350px;" v-model="assForm.site" auto-complete="off"></el-input>
              </el-form-item>
            </el-form>
            <div slot="footer" style="align-content: center" class="dialog-footer">
              <el-button type="primary" @click="submit()">提交</el-button>
              <el-button @click="closeDialog1()">取消</el-button>
            </div>
          </el-dialog>
        </div>
        <table id="placeStastics">
          <tr>
            <th>序号</th>
            <th>教师姓名</th>
            <th>工号</th>
            <th>教学实践类型</th>
            <th>教学实践地点</th>
            <th>状态</th>
            <th>操作</th>
          </tr>
          <tr v-for="(item,index) in assignList">
            <td>{{(currentpage-1)*8+index+1}}</td>
            <td>{{item.tName}}</td>
            <td>{{item.job_num}}</td>
            <td v-if="item.typeId==1">见习</td>
            <td v-if="item.typeId==2">实习</td>
            <td>{{item.site}}</td>
            <td v-if='item.status=="1"'>有效</td>
            <td v-if='item.status=="0"'>无效</td>
            <td>
              <span style="cursor: pointer" @click="alter(item.aId,item.tName)"><el-button type="success">修改</el-button></span>
              <span style="cursor: pointer" @click="del(item.aId,item.tName)"> <el-button type="danger">删除</el-button></span>
            </td>
          </tr>
        </table>
      </div>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
    export default {
        name: "placeArrange",
        data(){
            return{
                activeName:'first',
                inputName:'',
                currentpage: 1, // 当前页
                visiblepage: 10, // 可见页数
                totalpage: '', // 总页数
                assignList:[],
                formLabelWidth: '120px',
                dialogFormVisible:false,
                dialogFormVisible1:false,
                assForm:{
                    aId:'',//分配信息Id
                    tName:'',
                    job_num:'',
                    typeId:'',
                    site:''
                }
            }
        },
        methods:{
            handleClick(tab, event) {
                console.log(tab, event);
            },
            uploadFile(){
                this.imFile.click()
            },
            placeData(){
                //获取实习地点分配数据
                let that = this
                that.$http.post('/yii/practice/assign/getass',{page:that.currentpage})
                    .then((res)=>{
                        console.log(res.data)
                        that.assignList=res.data.data[0]
                        that.totalpage=res.data.data[1]
                        console.log(that.currentpage)
                    })
            },
            save(){
                //提交新增的实习分配信息
                this.$confirm('此操作的对象必须存在于数据库中?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    if(that.assForm.tName!=''&&that.assForm.job_num!=''&&that.assForm.typeId!=''&&that.assForm.site!=''){
                        that.$http.post('/yii/practice/assign/insertass',{
                            tname:that.assForm.tName,
                            jno:that.assForm.job_num,
                            typeId:that.assForm.typeId,
                            site:that.assForm.site
                        }).then((res)=>{
                            console.log(res.data)
                            if (res.data.message == "插入成功") {
                                alert("该条信息添加成功！")
                                that.dialogFormVisible = false
                                that.placeData()
                            }else if(res.data.message == "该教师不存在"){
                                alert("操作对象不在数据库中！")
                            }else if(res.data.message == "信息已存在"){
                                alert("请勿重复插入分配信息！")
                            }else{
                                alert("信息插入失败！")
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
            alter(id,uName){
                //修改实习分配信息
                let that =this
                that.$http.post('/yii/practice/assign/editass',{aId:id,name:uName})
                    .then((res)=>{
                        console.log(res.data)
                        that.assForm.aId=res.data.data.aId
                        that.assForm.tName=res.data.data.tName
                        that.assForm.job_num=res.data.data.job_num
                        that.assForm.typeId=res.data.data.typeId
                        that.assForm.site=res.data.data.site
                        that.dialogFormVisible1=true
                    })
            },
            submit(){
                //保留实习分配修改信息
                let that = this
                that.$http.post('/yii/practice/assign/alterass',{
                    aId:that.assForm.aId,
                    name:that.assForm.tName,
                    jno:that.assForm.job_num,
                    typeId:that.assForm.typeId,
                    site:that.assForm.site,
                }).then((res)=>{
                    console.log(res.data)
                    if (res.data.message=="success"){
                        alert('信息修改成功')
                    }else if(res.data.message=="failure"){
                        alert('信息修改失败')
                    }
                    that.placeData()
                    that.dialogFormVisible1=false
                }).catch((err)=>{
                    console.log(err)
                })
            },
            del(id,uName){
                this.$confirm('该操作将永久删除此条信息, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    that.$http.post('/yii/practice/assign/deleteass',{aId:id,name:uName})
                        .then((res)=>{
                            console.log(res.data)
                            if(res.data.message=="success"){
                                that.placeData()
                                alert("删除该条信息成功！")
                            }
                        })
                }).catch((err)=>{
                    console.log(err)
                })
            },
            addOne(){
                this.dialogFormVisible=true;
                this.assForm.tName='';
                this.assForm.job_num='';
                this.assForm.typeId='';
                this.assForm.site='';
            },
            resetass(){
                this.assForm.tName='';
                this.assForm.job_num='';
                this.assForm.typeId='';
                this.assForm.site='';
            },
            closeDialog(){
                this.dialogFormVisible=false;
            },
            closeDialog1(){
                this.dialogFormVisible1=false;
            },
            searchAssign(){
                //搜索实习分配信息
                let that = this
                this.$http.post('/yii/practice/assign/queryass',{
                    page:that.currentpage,
                    tName:that.inputName
                }).then(function(res){
                    console.log(res.data)
                    that.assignList=res.data.data[0]
                    that.totalpage =res.data.data[1]
                }).catch((err)=>{
                    console.log(err)
                })
            },
            importfxx(obj){
                let _this = this
                let inputDOM = this.$refs.inputer   // 通过DOM取文件数据
                this.file = event.currentTarget.files[0]
                var rABS = false // 是否将文件读取为二进制字符串
                var f = this.file
                var reader = new FileReader()
                // if (!FileReader.prototype.readAsBinaryString) {
                FileReader.prototype.readAsBinaryString = function (f) {
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
                        outdata.map(v => {
                            let obj = {}
                            obj.tName = v.教师姓名
                            obj.job_num = v.工号
                            obj.typeId = v.教学实践类型
                            obj.site= v.地点
                            arr.push(obj)
                        })
                        _this.memberList = [...arr]
                        let data = {
                            data: JSON.stringify(_this.memberList)
                        }
                        _this.$http.post('/yii/practice/assign/importexcel', data).then(body => {
                            console.log(body)
                            alert('导入成功')
                        })
                    }
                    reader.readAsArrayBuffer(f)
                }
                if (rABS) {
                    reader.readAsArrayBuffer(f)
                } else {
                    reader.readAsBinaryString(f)
                }
            }
        },
        mounted() {
            this.imFile = document.getElementById('imFile')
        },
        created() {
            this.placeData()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import "../../../common/css/pagination.css";
  @import "../../../common/css/layout.css";
</style>
