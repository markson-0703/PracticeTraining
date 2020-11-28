<template>
    <div class="display" id="maintop">
      <el-tabs  :tab-position="tabPosition" v-model="activeName" @tab-click="handleClick" style="height: fit-content;">
        <el-tab-pane label="课堂教学观摩记录" name="first"></el-tab-pane>
        <el-tab-pane label="课堂教学观摩讨论记录" name="second"></el-tab-pane>
        <el-tab-pane label="班级管理见习记录" name="third"></el-tab-pane>
        <el-tab-pane label="教研活动见习记录" name="fourth"></el-tab-pane>
        <el-tab-pane label="试讲教学设计" name="fifth">
          <div class="top-menu">
            <el-button type="primary" size="small" icon="el-icon-download" @click="downloadTem">模板下载</el-button>
            <el-upload class="file-upload" ref="fileForm" action="/yii/record/record/instruction"
                       :on-preview="handlePreview" :on-remove="handleRemove" :file-list="fileList" :auto-upload="false"
                       :on-change="handleChanged" :before-remove="beforeRemove" :on-success="uploadSuccess" :on-error="uploadError"
                       :data="uploadData" :before-upload="beforeUpload">
              <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
              <el-button style="margin-left: 10px;" size="small" type="success" @click="upload">文件上传</el-button>
              <div slot="tip" class="el-upload__tip">仅支持word文档的上传</div>
            </el-upload>
            <el-button size="small" @click="setPrintDetail()" style="background-color:tan;margin-right: 50px" type="primary" >预览</el-button>
          </div>
          <div class="record5">
            <div class="detail" id="pdfDom">
              <el-form ref="form" :model="recordForm5" :info="printDetail4" label-width="140px">
                <el-form-item label="日期:" style="width:400px;" prop="date">
                  <el-date-picker
                    v-model="recordForm5.date"
                    type="date"
                    placeholder="选择日期"
                    value-format="yyyy-MM-dd">
                  </el-date-picker>
                </el-form-item>
                <el-form-item label="星期:" style="width:400px;" prop="weekday">
                  <el-select v-model="recordForm5.weekday" placeholder="请选择具体时间">
                    <el-option label="星期一" value="1"></el-option>
                    <el-option label="星期二" value="2"></el-option>
                    <el-option label="星期三" value="3"></el-option>
                    <el-option label="星期四" value="4"></el-option>
                    <el-option label="星期五" value="5"></el-option>
                    <el-option label="星期六" value="6"></el-option>
                    <el-option label="星期日" value="7"></el-option>
                  </el-select>
                </el-form-item>
                <el-form-item label="第几节:" style="width:400px;" prop="section">
                  <el-input v-model="recordForm5.section"></el-input>
                </el-form-item>
                <el-form-item label="年级:" style="width:400px;" prop="grade">
                  <el-input v-model="recordForm5.grade"></el-input>
                </el-form-item>
                <el-form-item label="指导教师:" style="width:400px;" prop="tutor">
                  <el-input v-model="recordForm5.tutor"></el-input>
                </el-form-item>
                <el-form-item label="课型:" style="width:400px;" prop="classform">
                  <el-select v-model="recordForm5.classform" placeholder="请选择课型">
                    <el-option label="新授课" value="1"></el-option>
                    <el-option label="复习课" value="2"></el-option>
                    <el-option label="实验课" value="3"></el-option>
                    <el-option label="活动课" value="4"></el-option>
                  </el-select>
                </el-form-item>
                <el-form-item label="授课时间:" style="width:400px;" prop="teachTime">
                  <el-date-picker
                    v-model="recordForm5.teachTime"
                    type="date"
                    placeholder="选择日期"
                    value-format="yyyy-MM-dd">
                  </el-date-picker>
                </el-form-item>
                <el-form-item label="授课内容（章节）:" style="width:400px;" prop="teachContent">
                  <el-input v-model="recordForm5.teachContent"></el-input>
                </el-form-item>
                <el-form-item label="学习内容分析:" prop="contentAnalyse">
                  <quill-editor
                    v-model="recordForm5.contentAnalyse"
                    ref="myQuillEditor"
                    :options="custom"
                    @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
                    @change="onEditorChange($event)"
                  >
                  </quill-editor>
                </el-form-item>
                <el-form-item label="学习者分析:" prop="objectAnalyse">
                  <quill-editor
                    v-model="recordForm5.objectAnalyse"
                    ref="myQuillEditor"
                    :options="custom"
                    @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
                    @change="onEditorChange($event)"
                  >
                  </quill-editor>
                </el-form-item>
                <el-form-item label="教学目标:" prop="objections">
                  <quill-editor
                    v-model="recordForm5.objections"
                    ref="myQuillEditor"
                    :options="custom"
                    @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
                    @change="onEditorChange($event)"
                  >
                  </quill-editor>
                </el-form-item>
                <el-form-item label="教学重难点:" prop="difficulties">
                  <quill-editor
                    v-model="recordForm5.difficulties"
                    ref="myQuillEditor"
                    :options="custom"
                    @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
                    @change="onEditorChange($event)"
                  >
                  </quill-editor>
                </el-form-item>
                <el-form-item label="教学策略:" prop="strategies">
                  <quill-editor
                    v-model="recordForm5.strategies"
                    ref="myQuillEditor"
                    :options="custom"
                    @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
                    @change="onEditorChange($event)"
                  >
                  </quill-editor>
                </el-form-item>
                <el-form-item label="教学过程:" prop="process">
                  <quill-editor
                    v-model="recordForm5.process"
                    ref="myQuillEditor"
                    :options="custom"
                    @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
                    @change="onEditorChange($event)"
                  >
                  </quill-editor>
                </el-form-item>
                <el-form-item label="板书设计:" prop="writing">
                  <quill-editor
                    v-model="recordForm5.writing"
                    ref="myQuillEditor"
                    :options="custom"
                    @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
                    @change="onEditorChange($event)"
                  >
                  </quill-editor>
                </el-form-item>
                <el-form-item label="教学反思:" prop="reflect">
                  <quill-editor
                    v-model="recordForm5.reflect"
                    ref="myQuillEditor"
                    :options="custom"
                    @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
                    @change="onEditorChange($event)"
                  >
                  </quill-editor>
                </el-form-item>
              </el-form>
            </div>
            <div class="actions">
              <el-button type="primary" @click="saveLocal" size="small" style="background-color:#839206;margin-right:50px">保存</el-button>
              <el-button type="primary" @click="recordSubmit" size="small" style="background-color:goldenrod;margin-right:50px">提交</el-button>
              <el-button type="primary" @click="reset" size="small" style="background-color:darkcyan">重置</el-button>
            </div>
          </div>
        </el-tab-pane>
        <el-tab-pane label="试讲听课记录" name="sixth"></el-tab-pane>
      </el-tabs>
    </div>
</template>

<script>
    import htmlToPdf from"../../utils/htmlToPdf"
    import { quillEditor } from 'vue-quill-editor'
    import * as Quill from 'quill'  //引入编辑器
    //quill编辑器的字体
    var fonts = ['SimSun', 'SimHei','Microsoft-YaHei','KaiTi','FangSong','Arial','Times-New-Roman','sans-serif'];
    var Font = Quill.import('formats/font');
    Font.whitelist = fonts; //将字体加入到白名单
    Quill.register(Font, true);
    export default {
        name: "probationRecord4",
        data(){
            return{
                activeName:"fifth",
                tabPosition: 'left',
                printDetail4:'',
                currentTime:'',//当前时间
                username:'',
                fileList:[],
                uploadData:{
                },
                recordForm5:{
                    date:'',
                    weekday:'',
                    section:'',
                    grade:'',
                    tutor:'',
                    classform:'',
                    teachTime:'',
                    teachContent:'',
                    contentAnalyse:'',
                    objectAnalyse:'',
                    objections:'',
                    difficulties:'',
                    strategies:'',
                    process:'',
                    writing:'',
                    reflect:'',
                },
                custom:{
                    //自定义工具栏
                    // theme:'snow',
                    theme:'bubble',
                    placeholder: "请在这里输入",
                    modules:{
                        toolbar:[
                            ['bold', 'italic', 'underline', 'strike'],        //加粗，斜体，下划线，删除线
                            // ['blockquote', 'code-block'],         //引用，代码块

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
                            // ['link','image','video']        //上传图片、上传视频
                        ]
                    }
                },
            }
        },
        methods:{
            handlePreview(file){
                //点击文件列表中已上传的文件时的钩子
                console.log(file);//打印出了文件信息
            },
            handleRemove(file){
                //文件列表移除文件时的钩子
                console.log(file);
            },
            handleChanged(file){
                //文件状态改变时的钩子，添加文件、上传成功和上传失败时都会被调用
                console.log(file)
            },
            beforeRemove(file) {
                return this.$confirm(`确定移除 ${ file.name }？`);
            },
            beforeUpload(){
                //在上传文件之前，先上传需要的变量
                this.uploadData = {username:this.$store.getters.getsName,}
                let promise = new Promise((resolve) => {
                    this.$nextTick(function () {
                        resolve(true);
                    });
                });
                return promise;
            },
            uploadSuccess(res){
                if(res.code==200){
                    this.$message({
                        message: '恭喜你，教学设计上传成功',
                        type: 'success'
                    });
                    // this.getTemplate()
                    console.log(res.data)
                }else{
                    this.$message({
                        message: res.message,
                        type: 'warning'
                    });
                }
            },
            upload(){
                //上传
                alert("请在导师审阅后上传最终教学设计！")
                this.$refs.fileForm.submit()
            },
            uploadError(){
                this.$refs.fileForm.clearFiles();
                this.$message.error('上传失败，请重新上传!');
            },
            //定义时间
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
            handleClick(tab, event) {
                if(this.activeName=="first"){
                    this.$router.push('/student/probation/probationRecord')
                }else if(this.activeName=="second"){
                    this.$router.push('/student/probation/probationRecord1')
                }else if(this.activeName=="third"){
                    this.$router.push('/student/probation/probationRecord2')
                }else if(this.activeName=="fourth"){
                    this.$router.push('/student/probation/probationRecord3')
                }else if(this.activeName=="sixth"){
                    this.$router.push('/student/probation/probationRecord5')
                }
            },
            setPrintDetail(info){
                //预览
                this.printDetail4=info
                if(this.$refs.form.$el) {
                    console.log(this.$refs.form.$el)
                    this.$nextTick(() => {
                        this.$refs.form.$el.innerHTML;
                        this.getPdf(document.querySelector('#pdfDom'));//在main中导入，所以可以直接使用
                    })
                }
            },
            saveLocal(){
                //保存到本地
                this.$confirm('请先预览，确保无误后再保存到本地?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    this.currentTime= this.format(new Date(), "yyyy-MM-dd HH:mm:ss");//获取当前时间
                    htmlToPdf.downloadPDF(document.querySelector('#pdfDom'), this.currentTime)//以当前时间作为文件名
                })
            },
            recordSubmit(){
                console.log(this.recordForm5.difficulties)
                //记录提交
                this.$confirm('请先确保无误后再上传?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    if(that.recordForm5.weekday=="1"){
                        that.recordForm5.weekday="星期一"
                    }else if(that.recordForm5.weekday=="2"){
                        that.recordForm5.weekday="星期二"
                    }else if(that.recordForm5.weekday=="3"){
                        that.recordForm5.weekday="星期三"
                    }else if(that.recordForm5.weekday=="4"){
                        that.recordForm5.weekday="星期四"
                    }else if(that.recordForm5.weekday=="5"){
                        that.recordForm5.weekday="星期五"
                    }else if(that.recordForm5.weekday=="6"){
                        that.recordForm5.weekday="星期六"
                    }else if(that.recordForm5.weekday=="7"){
                        that.recordForm5.weekday="星期日"
                    }
                    if(that.recordForm5.classform=="1"){
                        that.recordForm5.classform="新授课"
                    }else if(that.recordForm5.classform=="2"){
                        that.recordForm5.classform="复习课"
                    }else if(that.recordForm5.classform=="3"){
                        that.recordForm5.classform="实验课"
                    }else if(that.recordForm5.classform=="4"){
                        that.recordForm5.classform="活动课"
                    }
                    //去除p标签
                    that.recordForm5.contentAnalyse= that.recordForm5.contentAnalyse.replace(/<[^>]+>/g, ""),
                    that.recordForm5.objectAnalyse= that.recordForm5.objectAnalyse.replace(/<[^>]+>/g, ""),
                    that.recordForm5.difficulties= that.recordForm5.difficulties.replace(/<[^>]+>/g, ""),
                    that.recordForm5.objections= that.recordForm5.objections.replace(/<[^>]+>/g, ""),
                    that.recordForm5.strategies= that.recordForm5.strategies.replace(/<[^>]+>/g, ""),
                    that.recordForm5.process= that.recordForm5.process.replace(/<[^>]+>/g, ""),
                    that.recordForm5.writing= that.recordForm5.writing.replace(/<[^>]+>/g, ""),
                    that.recordForm5.reflect= that.recordForm5.reflect.replace(/<[^>]+>/g, ""),
                    that.$http.post('/yii/record/template/submitrecord4',{
                        username:that.username,
                        date:that.recordForm5.date,
                        weekday:that.recordForm5.weekday,
                        section:that.recordForm5.section,
                        grade:that.recordForm5.grade,
                        tutor:that.recordForm5.tutor,
                        classform:that.recordForm5.classform,
                        teachTime:that.recordForm5.teachTime,
                        teachContent:that.recordForm5.teachContent,
                        contentAnalyse:that.recordForm5.contentAnalyse,
                        objectAnalyse:that.recordForm5.objectAnalyse,
                        objections:that.recordForm5.objections,
                        difficulties:that.recordForm5.difficulties,
                        strategies:that.recordForm5.strategies,
                        process:that.recordForm5.process,
                        writing:that.recordForm5.writing,
                        reflect:that.recordForm5.reflect,
                        submitTime:that.format(new Date(), "yyyy-MM-dd HH:mm:ss")
                    }).then(function(res){
                        console.log(res.data)
                        if(res.data.message=="success"){
                            alert("提交成功!")
                            // this.createPdf(res.data.data)
                        }else{
                            alert("提交失败!")
                        }
                    })
                })
            },
            reset(){
                this.$refs.form.resetFields();
            },
            onEditorBlur(){//失去焦点事件
            },
            onEditorFocus(){//获得焦点事件
            },
            onEditorChange(){//内容改变事件
            },
            downloadTem(){
                //模板下载
                let that = this
                that.$http.post('/yii/record/record/mytemplate',{
                    kind:5
                }).then(function (res) {
                    console.log(res.data)
                    var originUrl='http://127.0.0.1/'+res.data.data//要预览文件的访问地址
                    window.open(originUrl)
                })
            }
        },
        created(){
            this.username=this.$store.getters.getsName
        },
    }
</script>

<style scoped="scoped" type="text/css">
  @import '../../../common/css/font.css';
  .file-upload{
    float:right
  }
  .display{
    text-align: center;
    height:100%;
  }
  .record5{
    display: inline-block;
    font-weight: bolder;
  }
  .top-menu{
    float:left;
    padding-bottom: 10px;
  }
  .detail{
    padding:0px
  }
  .actions{
    margin:0px;
  }
</style>
