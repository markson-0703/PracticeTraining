<template>
  <div class="display" id="maintop" ref="box">
    <!--      显示教学设计内容-->
      <div class="detail" id="pdfDom" v-show="this.contentVisible">
      <el-form ref="form" :model="Form" :info="printDetail" label-width="140px">
        <el-form-item label="日期:" style="width:400px;" prop="date">
          <el-date-picker
            v-model="Form.date"
            type="date"
            placeholder="选择日期"
            value-format="yyyy-MM-dd">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="星期:" style="width:400px;" prop="weekday">
          <el-select v-model="Form.weekday" placeholder="请选择具体时间">
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
          <el-input v-model="Form.section"></el-input>
        </el-form-item>
        <el-form-item label="年级:" style="width:400px;" prop="grade">
          <el-input v-model="Form.grade"></el-input>
        </el-form-item>
        <el-form-item label="指导教师:" style="width:400px;" prop="tutor">
          <el-input v-model="Form.tutor"></el-input>
        </el-form-item>
        <el-form-item label="课型:" style="width:400px;" prop="classform">
          <el-select v-model="Form.classform" placeholder="请选择课型">
            <el-option label="新授课" value="1"></el-option>
            <el-option label="复习课" value="2"></el-option>
            <el-option label="实验课" value="3"></el-option>
            <el-option label="活动课" value="4"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="授课时间:" style="width:400px;" prop="teachTime">
          <el-date-picker
            v-model="Form.teachTime"
            type="date"
            placeholder="选择日期"
            value-format="yyyy-MM-dd">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="授课内容（章节）:" style="width:400px;" prop="teachContent">
          <el-input v-model="Form.teachContent"></el-input>
        </el-form-item>
        <el-form-item label="学习内容分析:" prop="contentAnalyse">
          <quill-editor
            v-model="Form.contentAnalyse"
            ref="myQuillEditor"
            :options="custom"
            @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
            @change="onEditorChange($event)"
          >
          </quill-editor>
        </el-form-item>
        <el-form-item label="学习者分析:" prop="objectAnalyse">
          <quill-editor
            v-model="Form.objectAnalyse"
            ref="myQuillEditor"
            :options="custom"
            @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
            @change="onEditorChange($event)"
          >
          </quill-editor>
        </el-form-item>
        <el-form-item label="教学目标:" prop="objections">
          <quill-editor
            v-model="Form.objections"
            ref="myQuillEditor"
            :options="custom"
            @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
            @change="onEditorChange($event)"
          >
          </quill-editor>
        </el-form-item>
        <el-form-item label="教学重难点:" prop="difficulties">
          <quill-editor
            v-model="Form.difficulties"
            ref="myQuillEditor"
            :options="custom"
            @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
            @change="onEditorChange($event)"
          >
          </quill-editor>
        </el-form-item>
        <el-form-item label="教学策略:" prop="strategies">
          <quill-editor
            v-model="Form.strategies"
            ref="myQuillEditor"
            :options="custom"
            @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
            @change="onEditorChange($event)"
          >
          </quill-editor>
        </el-form-item>
        <el-form-item label="教学过程:" prop="process">
          <quill-editor
            v-model="Form.process"
            ref="myQuillEditor"
            :options="custom"
            @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
            @change="onEditorChange($event)"
          >
          </quill-editor>
        </el-form-item>
        <el-form-item label="板书设计:" prop="writing">
          <quill-editor
            v-model="Form.writing"
            ref="myQuillEditor"
            :options="custom"
            @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
            @change="onEditorChange($event)"
          >
          </quill-editor>
        </el-form-item>
        <el-form-item label="教学反思:" prop="reflect">
          <quill-editor
            v-model="Form.reflect"
            ref="myQuillEditor"
            :options="custom"
            @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
            @change="onEditorChange($event)"
          >
          </quill-editor>
        </el-form-item>
      </el-form>
      </div>
  </div>
</template>

<script>
    export default {
        name: "instruction",
        data() {
            return {
                id: '',//当前见习教学设计的id
                printDetail: '',
                contentVisible: false,
                Form: {
                    date: '',
                    weekday: '',
                    section: '',
                    grade: '',
                    tutor: '',
                    classform: '',
                    teachTime: '',
                    teachContent: '',
                    contentAnalyse: '',
                    objectAnalyse: '',
                    objections: '',
                    difficulties: '',
                    strategies: '',
                    process: '',
                    writing: '',
                    reflect: '',
                },
                custom: {
                    //自定义工具栏
                    // theme:'snow',
                    theme: 'bubble',
                    placeholder: "请在这里输入",
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],        //加粗，斜体，下划线，删除线
                            // ['blockquote', 'code-block'],         //引用，代码块

                            [{'header': 1}, {'header': 2}],               // 标题，键值对的形式；1、2表示字体大小
                            [{'list': 'ordered'}, {'list': 'bullet'}],          //列表
                            [{'script': 'sub'}, {'script': 'super'}],      // 上下标
                            [{'indent': '-1'}, {'indent': '+1'}],          // 缩进
                            [{'direction': 'rtl'}],                         // 文本方向
                            [{'size': ['small', false, 'large', 'huge']}],  // 字体大小
                            [{'header': [1, 2, 3, 4, 5, 6, false]}],         //几级标题

                            [{'color': []}, {'background': []}],          // 字体颜色，字体背景颜色
                            [{'font': []}],         //字体
                            [{'align': []}],        //对齐方式

                            ['clean'],        //清除字体样式
                            ['upload']
                            // ['link','image','video']        //上传图片、上传视频
                        ]
                    }
                },
            }
        },
        methods: {
            onEditorBlur() {//失去焦点事件
            },
            onEditorFocus() {//获得焦点事件
            },
            onEditorChange() {//内容改变事件
            },
            content() {
                let that = this
                that.$http.post('/yii/resource/resource/oneinstruction', {
                    id: that.id
                }).then(function (res) {
                    console.log(res.data)
                    that.Form.date = res.data.data.date
                    that.Form.weekday = res.data.data.weekday
                    that.Form.section = res.data.data.section
                    that.Form.grade = res.data.data.grade
                    that.Form.tutor = res.data.data.tutor
                    that.Form.classform = res.data.data.classform
                    that.Form.teachTime = res.data.data.teachTime
                    that.Form.teachContent = res.data.data.teachContent
                    that.Form.contentAnalyse = res.data.data.contentAnalyse
                    that.Form.objectAnalyse = res.data.data.objectAnalyse
                    that.Form.objections = res.data.data.objections
                    that.Form.difficulties = res.data.data.difficulties
                    that.Form.strategies = res.data.data.strategies
                    that.Form.process = res.data.data.process
                    that.Form.writing = res.data.data.writing
                    that.Form.reflect = res.data.data.reflect
                    that.contentVisible = true
                    console.log(this.$refs.box.scrollTop)
                    setTimeout(()=>{
                        that.setPrintDetail()
                    }, 2000);
                })
            },
            setPrintDetail(info) {
                //查看教学设计
                this.printDetail = info
                console.log(this.$refs.form)
                if (this.$refs.form.$el) {
                    console.log(this.$refs.form.$el)
                    this.$nextTick(() => {
                     this.$refs.form.$el.innerHTML;
                     this.getPdf(document.querySelector('#pdfDom'));//在main中导入，所以可以直接使用
                    })
                }
            },
        },
        created() {
            this.id = this.$route.params.id
            console.log(this.id)
            this.content()
        },
        mounted() {
            window.scrollTo(0, 0);
        }
    }
</script>

<style scoped="scoped" type="text/css">
  .display{
    text-align: center;
    height:100%;
  }
  .detail{
    display: inline-block;
  }
</style>
