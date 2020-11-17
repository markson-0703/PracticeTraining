<template>
  <div class="display" id="maintop">
    <el-tabs  :tab-position="tabPosition" v-model="activeName" @tab-click="handleClick" style="height: fit-content;">
      <el-tab-pane label="课堂教学观摩记录" name="first">
        <div class="record1">
          <div class="detail" id="pdfDom">
          <el-form ref="form" :model="recordForm1" :info="printDetail" label-width="80px">
            <el-form-item label="形式:" style="width:400px;" prop="type">
              <el-select v-model="recordForm1.type" placeholder="请选择见习形式">
                <el-option label="网络见习" value="1"></el-option>
                <el-option label="学校见习" value="2"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="日期:" style="width:400px;" prop="date">
              <el-date-picker
                v-model="recordForm1.date"
                type="date"
                placeholder="选择日期"
                value-format="yyyy-MM-dd">
              </el-date-picker>
            </el-form-item>
            <el-form-item label="具体时间:" style="width:400px;" prop="weekday">
              <el-select v-model="recordForm1.weekday" placeholder="请选择具体时间">
                <el-option label="星期一" value="1"></el-option>
                <el-option label="星期二" value="2"></el-option>
                <el-option label="星期三" value="3"></el-option>
                <el-option label="星期四" value="4"></el-option>
                <el-option label="星期五" value="5"></el-option>
                <el-option label="星期六" value="6"></el-option>
                <el-option label="星期日" value="7"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="见习班级:" style="width:400px;" prop="className">
              <el-input v-model="recordForm1.className"></el-input>
            </el-form-item>
            <el-form-item label="授课老师:" style="width:400px;" prop="instructor">
              <el-input v-model="recordForm1.instructor"></el-input>
            </el-form-item>
            <el-form-item label="课题:" style="width:400px;" prop="theme">
              <el-input v-model="recordForm1.theme"></el-input>
            </el-form-item>
            <el-form-item label="课型:" style="width:400px;" prop="classform">
              <el-select v-model="recordForm1.classform" placeholder="请选择课型">
                <el-option label="新授课" value="1"></el-option>
                <el-option label="复习课" value="2"></el-option>
                <el-option label="实验课" value="3"></el-option>
                <el-option label="活动课" value="4"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="听课记录:" prop="content1">
              <quill-editor
                v-model="recordForm1.content1"
                ref="myQuillEditor"
                :options="custom"
                @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
                @change="onEditorChange($event)"
              >
              </quill-editor>
            </el-form-item>
            <el-form-item label="听课反思:" prop="content2">
              <quill-editor
                v-model="recordForm1.content2"
                ref="myQuillEditor"
                :options="custom"
                @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
                @change="onEditorChange($event)"
              >
              </quill-editor>
            </el-form-item>
            <el-form-item label="教师指导意见记录:" prop="content3">
              <quill-editor
                v-model="recordForm1.content3"
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
            <el-button size="small" @click="setPrintDetail()" style="background-color:tan;margin-right: 50px" type="primary" >预览</el-button>
            <el-button type="primary" @click="saveLocal" size="small" style="background-color:#839206;margin-right:50px">保存</el-button>
            <el-button type="primary" @click="recordSubmit" size="small" style="background-color:goldenrod;margin-right:50px">提交</el-button>
            <el-button type="primary" @click="reset" size="small" style="background-color:darkcyan">重置</el-button>
            <el-button type="primary" @click="test" size="small" style="background-color:darkcyan">测试</el-button>
          </div>
        </div>
      </el-tab-pane>
      <el-tab-pane label="课堂教学观摩讨论记录" name="second"></el-tab-pane>
      <el-tab-pane label="班级管理见习记录" name="third"></el-tab-pane>
      <el-tab-pane label="教研活动见习记录" name="fourth"></el-tab-pane>
      <el-tab-pane label="试讲教学设计" name="fifth"></el-tab-pane>
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
    import { addQuillTitle } from '../../../common/js/quill-title'
    export default {
        name: "probationRecord",
        data(){
            return{
                username:'',//用户名
                currentTime:'',//当前时间
                printDetail:'',
                activeName:"first",
                tabPosition: 'left',
                recordForm1:{
                    type:'',
                    date:'',
                    weekday:'',
                    className:'',
                    instructor:'',
                    theme:'',
                    classform:'',
                    content1:'',
                    content2:'',
                    content3:''
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
                fileData:[]
            }
        },
        methods:{
            handleClick(tab, event) {
                if(this.activeName=="second"){
                  this.$router.push('/student/probation/probationRecord1')
                }else if(this.activeName=="third"){
                  this.$router.push('/student/probation/probationRecord2')
                }
            },
            onEditorBlur(){//失去焦点事件
            },
            onEditorFocus(){//获得焦点事件
            },
            onEditorChange(){//内容改变事件
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
                //上传到数据库
                this.$confirm('请先预览，确保无误后再上传?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    if(that.recordForm1.type=="1"){
                        that.recordForm1.type="网络见习"
                    }else if(that.recordForm1.type=="2"){
                        that.recordForm1.type="学校见习"
                    }
                    if(that.recordForm1.weekday=="1"){
                        that.recordForm1.weekday="星期一"
                    }else if(that.recordForm1.weekday=="2"){
                        that.recordForm1.weekday="星期二"
                    }else if(that.recordForm1.weekday=="3"){
                        that.recordForm1.weekday="星期三"
                    }else if(that.recordForm1.weekday=="4"){
                        that.recordForm1.weekday="星期四"
                    }else if(that.recordForm1.weekday=="5"){
                        that.recordForm1.weekday="星期五"
                    }else if(that.recordForm1.weekday=="6"){
                        that.recordForm1.weekday="星期六"
                    }else if(that.recordForm1.weekday=="7"){
                        that.recordForm1.weekday="星期日"
                    }
                    if(that.recordForm1.classform=="1"){
                        that.recordForm1.classform="新授课"
                    }else if(that.recordForm1.classform=="2"){
                        that.recordForm1.classform="复习课"
                    }else if(that.recordForm1.classform=="3"){
                        that.recordForm1.classform="实验课"
                    }else if(that.recordForm1.classform=="4"){
                        that.recordForm1.classform="活动课"
                    }
                    that.recordForm1.content1= that.recordForm1.content1.replace("<p>", "")
                    that.recordForm1.content2= that.recordForm1.content2.replace("<p>", "")
                    that.recordForm1.content3= that.recordForm1.content3.replace("<p>", "")
                    that.recordForm1.content1= that.recordForm1.content1.replace("</p>", "")
                    that.recordForm1.content2= that.recordForm1.content2.replace("</p>", "")
                    that.recordForm1.content3= that.recordForm1.content3.replace("</p>", "")
                    that.$http.post('/yii/record/record/submitrecord',{
                        username:that.username,
                        type:that.recordForm1.type,
                        date:that.recordForm1.date,
                        weekday:that.recordForm1.weekday,
                        className:that.recordForm1.className,
                        instructor:that.recordForm1.instructor,
                        theme:that.recordForm1.theme,
                        classform:that.recordForm1.classform,
                        content1:that.recordForm1.content1,
                        content2:that.recordForm1.content2,
                        content3:that.recordForm1.content3,
                        submitTime:that.format(new Date(), "yyyy-MM-dd HH:mm:ss")
                    }).then((res)=>{
                        console.log(res.data)
                        if(res.data.message=="success"){
                            alert("提交成功!")
                            this.savefile(res.data.data)
                        }else{
                            alert("提交失败!")
                        }
                    })
                })
            },
            reset(){
                this.$refs.form.resetFields();
            },
            setPrintDetail(info){
                //预览记录
                this.printDetail=info
                if(this.$refs.form.$el) {
                    console.log(this.$refs.form.$el)
                    this.$nextTick(() => {
                        this.$refs.form.$el.innerHTML;
                        this.getPdf(document.querySelector('#pdfDom'));//在main中导入，所以可以直接使用
                    })
                }
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
            savefile(time){
                //显示PDF测试
                let that = this
                that.$http.post('/yii/record/record/exportpdf',{
                    time:time,
                    username:that.username
                }).then((res)=>{
                    console.log(res.data)
                })
            },
            test(){
                let that = this
                that.$http.post('/yii/record/record/getpdf',{
                    uId:1
                }).then((res)=>{
                    console.log(res.data.data)
                    var url='127.0.0.1/'+res.data.data
                    window.open('http://'+url)
              })
            }
        },
        created(){
            this.username=this.$store.getters.getsName
        },
        mounted() {
            //在生命周期函数中调用，实现了鼠标悬停按钮完成tooltip提示
            addQuillTitle();
        }
    }
</script>

<style scoped="scoped" type="text/css">
 @import '../../../common/css/font.css';
  .display{
    text-align: center;
    height:100%;
  }
 .record1{
   display: inline-block;
   font-weight: bolder;
 }
 .detail{
   padding:0px
 }
  .actions{
   margin:0px;
  }

</style>
