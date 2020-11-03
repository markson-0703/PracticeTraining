<template>
  <div class="display" id="maintop">
    <el-tabs  :tab-position="tabPosition" v-model="activeName" @tab-click="handleClick" style="height: fit-content;">
      <el-tab-pane label="课堂教学观摩记录" name="first" >
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
                <el-option label="星期日" value="6"></el-option>
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
            <el-button type="primary" @click="recordSubmit" size="small" style="background-color:#839206;margin-right: 50px">保存</el-button>
            <el-button type="primary" @click="reset" size="small" style="background-color:darkcyan">重置</el-button>
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
                }
            }
        },
        methods:{
            handleClick(tab, event) {
                if(this.activeName=="second"){
                  this.$router.push('/student/probation/probationRecord1')
                }
            },
            onEditorBlur(){//失去焦点事件
            },
            onEditorFocus(){//获得焦点事件
            },
            onEditorChange(){//内容改变事件
            },
            recordSubmit(){

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
            }
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
