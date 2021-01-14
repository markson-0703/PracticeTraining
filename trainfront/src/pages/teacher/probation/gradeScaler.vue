<template>
  <div class="display">
    <div>
      <span style="float:left;color:brown;font-weight: bolder;cursor: pointer" @click="backone"><i class="el-icon-back" style="font-size: 22px"></i>返回</span>
      <h3 style="color:dodgerblue;margin:0px">见习生教育见习成绩评定标准</h3>
    </div>
    <div class="scale">
    <table cellspacing="0" cellpadding="10" id="markStastics">
<!--    表头-->
      <thead>
      <tr>
        <th style="width:10%">一级指标</th>
        <th style="width:10%">二级指标</th>
        <th style="width:10%">主要测观点</th>
        <th colspan="2" style="width:30%">评估标准</th>
        <th style="width:10%">分值</th>
        <th colspan="4" style="width:20%">评估标准</th>
        <th style="width:10%">备注</th>
      </tr>
      </thead>
<!--   表体-->
      <tbody>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>A级</td>
        <td>C级</td>
        <td></td>
        <td>A</td>
        <td>B</td>
        <td>C</td>
        <td>D</td>
        <td></td>
      </tr>
      <tr>
        <td rowspan="3">行为表率(10%)</td>
        <td>见习态度与纪律</td>
        <td>见习态度与纪律</td>
        <td>见习态度始终认真，能遵守学校规章制度，认真完成各项工作，表现突出</td>
        <td>见习态度尚认真，但有违反纪律及不积极完成任务的表现</td>
        <td>3</td>
        <td colspan="4">
          <el-radio-group v-model="radioList.radio1" @change="selectRadio">
            <el-radio class="radio" label=3>{{''}}</el-radio>
            <el-radio class="radio" label=2.4>{{''}}</el-radio>
            <el-radio class="radio" label=1.8>{{''}}</el-radio>
            <el-radio class="radio" label=1.2>{{''}}</el-radio>
          </el-radio-group>
        </td>
        <td></td>
      </tr>
      <tr>
        <td>为人师表</td>
        <td>为人师表</td>
        <td>举止端庄，尊敬师长，团结互助，严于律己，成绩突出</td>
        <td>言行举止比较随便，对自己要求不够严格，为人师表方面表现一般</td>
        <td>4</td>
        <td colspan="4">
          <el-radio-group v-model="radioList.radio2" @change="selectRadio1">
            <el-radio class="radio" label=4>{{''}}</el-radio>
            <el-radio class="radio" label=3.2>{{''}}</el-radio>
            <el-radio class="radio" label=2.4>{{''}}</el-radio>
            <el-radio class="radio" label=1.6>{{''}}</el-radio>
          </el-radio-group>
        </td>
        <td></td>
      </tr>
      <tr>
        <td>教学相长</td>
        <td>教学相长</td>
        <td>虚心求教，热爱学生，与见习学校的教职员工关系融洽，受到见习学校好评</td>
        <td>尚能接收指导教师的意见，对学生关心不够，师生关系一般</td>
        <td>3</td>
        <td colspan="4">
          <el-radio-group v-model="radioList.radio3" @change="selectRadio2">
            <el-radio class="radio" label=3>{{''}}</el-radio>
            <el-radio class="radio" label=2.4>{{''}}</el-radio>
            <el-radio class="radio" label=1.8>{{''}}</el-radio>
            <el-radio class="radio" label=1.2>{{''}}</el-radio>
          </el-radio-group>
        </td>
        <td></td>
      </tr>
      <tr>
        <td rowspan="3">课堂教学见习(30%)</td>
        <td rowspan="3">课堂观摩</td>
        <td>听课状态</td>
        <td>听课认真，积极思考，将自己完全融入课堂，不受外界环境的干扰</td>
        <td>听课积极性一般，基本能够跟随课堂进度，容易开小差</td>
        <td>10</td>
        <td colspan="4">
          <el-radio-group v-model="radioList.radio4" @change="selectRadio3">
            <el-radio class="radio" label=10>{{''}}</el-radio>
            <el-radio class="radio" label=8>{{''}}</el-radio>
            <el-radio class="radio" label=6>{{''}}</el-radio>
            <el-radio class="radio" label=4>{{''}}</el-radio>
          </el-radio-group>
        </td>
        <td></td>
      </tr>
      <tr>
        <td>观摩记录</td>
        <td>观摩记录条理清晰，主次分明，能够概要记录全课教学环节，重点记录授课教师教学情境的创设，活动的设计与组织，教学重难点解决，教学关键环节转换等
        ，而且记录了自己对课堂的理解与疑问</td>
        <td>基本能够以见习教师的身份完成观摩记录，但记录主次不分明，只能完成对教学基本流程的记录，没有自己的见解和看法</td>
        <td>10</td>
        <td colspan="4">
          <el-radio-group v-model="radioList.radio5" @change="selectRadio4">
            <el-radio class="radio" label=10>{{''}}</el-radio>
            <el-radio class="radio" label=8>{{''}}</el-radio>
            <el-radio class="radio" label=6>{{''}}</el-radio>
            <el-radio class="radio" label=4>{{''}}</el-radio>
          </el-radio-group>
        </td>
        <td></td>
      </tr>
      <tr>
        <td>评课议课</td>
        <td>能够以教师及鉴赏者的身份品评所观摩的课堂，对授课教师的教学风格、教学艺术、课堂创新等进行全方位总结，并准确表达自己的感受与见解，同时就观摩过程中产生
        的疑惑与授课教师、指导教师及同学进行沟通交流，碰撞出思想的火花</td>
        <td>能够大概阐述观摩课中值得自己学习的地方，但不够全面，见解不深，不能深度挖掘出授课教师的教学亮点，与授课教师、指导教师及同伴的交流流于形式，讨论效果一般</td>
        <td>10</td>
        <td colspan="4">
          <el-radio-group v-model="radioList.radio6" @change="selectRadio5">
            <el-radio class="radio" label=10>{{''}}</el-radio>
            <el-radio class="radio" label=8>{{''}}</el-radio>
            <el-radio class="radio" label=6>{{''}}</el-radio>
            <el-radio class="radio" label=4>{{''}}</el-radio>
          </el-radio-group>
        </td>
        <td></td>
      </tr>
      <tr>
        <td>参与班级管理(10%)</td>
        <td></td>
        <td></td>
        <td>能够通过细致观察，积极与见习班级的班主任、学生进行沟通交流，观摩班会课等方法，了解班级制度建设、文化建设、教学管理等内容，并做好记录，写下自己的见解和感悟</td>
        <td>能够基本感知所见习班级在班级管理中所表现出的闪光点，但观察不细致，缺乏与班主任及学生的沟通交流，了解不全面，且缺乏自己的感悟与见解</td>
        <td>10</td>
        <td colspan="4">
          <el-radio-group v-model="radioList.radio7" @change="selectRadio6">
            <el-radio class="radio" label=10>{{''}}</el-radio>
            <el-radio class="radio" label=8>{{''}}</el-radio>
            <el-radio class="radio" label=6>{{''}}</el-radio>
            <el-radio class="radio" label=4>{{''}}</el-radio>
          </el-radio-group>
        </td>
        <td></td>
      </tr>
      <tr>
        <td>参与教研活动(10%)</td>
        <td></td>
        <td></td>
        <td>能够积极参与见习学校的教研活动，了解教研活动的基本形式与方法，并记录自己的所得所想</td>
        <td>能够基本了解教研活动的基本形式与方法，但参与度不够，缺乏独立思考</td>
        <td>10</td>
        <td colspan="4">
          <el-radio-group v-model="radioList.radio8" @change="selectRadio7">
            <el-radio class="radio" label=10>{{''}}</el-radio>
            <el-radio class="radio" label=8>{{''}}</el-radio>
            <el-radio class="radio" label=6>{{''}}</el-radio>
            <el-radio class="radio" label=4>{{''}}</el-radio>
          </el-radio-group>
        </td>
        <td></td>
      </tr>
      <tr>
        <td rowspan="6">试讲(30%)</td>
        <td>课前准备</td>
        <td>教案</td>
        <td>能够在见习前详细了解听课班级的情况，包括班风、学风，学生整体水平及教学进度等，并在此基础上独立按时完成教案的设计，且备课认真，教案完整，质量较高</td>
        <td>备课尚努力，经多次帮助，教案尚完整，且能按时完成，但质量较差，没有基于所听课班级的基本情况进行设计</td>
        <td>5</td>
        <td colspan="4">
          <el-radio-group v-model="radioList.radio9" @change="selectRadio8">
            <el-radio class="radio" label=5>{{''}}</el-radio>
            <el-radio class="radio" label=4>{{''}}</el-radio>
            <el-radio class="radio" label=3>{{''}}</el-radio>
            <el-radio class="radio" label=2>{{''}}</el-radio>
          </el-radio-group>
        </td>
        <td></td>
      </tr>
      <tr>
        <td rowspan="5">课堂教学</td>
        <td>预讲</td>
        <td>内容熟悉，教态自然，符合教学要求</td>
        <td>内容欠熟悉，试讲有缺陷，尚能接受意见，改进不大</td>
        <td>5</td>
        <td colspan="4">
          <el-radio-group v-model="radioList.radio10" @change="selectRadio9">
            <el-radio class="radio" label=5>{{''}}</el-radio>
            <el-radio class="radio" label=4>{{''}}</el-radio>
            <el-radio class="radio" label=3>{{''}}</el-radio>
            <el-radio class="radio" label=2>{{''}}</el-radio>
          </el-radio-group>
        </td>
        <td></td>
      </tr>
      <tr>
        <td>讲课</td>
        <td>目的明确，重点突出，难点抓的准，内容科学系统</td>
        <td>目的基本明确，重点不突出，难点未突破，有科学性缺陷</td>
        <td>5</td>
        <td colspan="4">
          <el-radio-group v-model="radioList.radio11" @change="selectRadio10">
            <el-radio class="radio" label=5>{{''}}</el-radio>
            <el-radio class="radio" label=4>{{''}}</el-radio>
            <el-radio class="radio" label=3>{{''}}</el-radio>
            <el-radio class="radio" label=2>{{''}}</el-radio>
          </el-radio-group>
        </td>
        <td></td>
      </tr>
      <tr>
        <td>教学方法</td>
        <td>启发性强，开展双边活动，能用标准普通话教学，语言简洁、流畅，板书安排有序，文字规范、美观</td>
        <td>不注意运用启发式，开展双边活动差；普通话不标准；板书无计划，有错别字</td>
        <td>5</td>
        <td colspan="4">
          <el-radio-group v-model="radioList.radio12" @change="selectRadio11">
            <el-radio class="radio" label=5>{{''}}</el-radio>
            <el-radio class="radio" label=4>{{''}}</el-radio>
            <el-radio class="radio" label=3>{{''}}</el-radio>
            <el-radio class="radio" label=2>{{''}}</el-radio>
          </el-radio-group>
        </td>
        <td></td>
      </tr>
      <tr>
        <td>教学组织</td>
        <td>课堂教学组织严密，课堂秩序活而不乱，应变能力较强</td>
        <td>对学生有一定的要求，但不能控制少数学生，课堂秩序尚好，缺乏应变能力</td>
        <td>5</td>
        <td colspan="4">
          <el-radio-group v-model="radioList.radio13" @change="selectRadio12">
            <el-radio class="radio" label=5>{{''}}</el-radio>
            <el-radio class="radio" label=4>{{''}}</el-radio>
            <el-radio class="radio" label=3>{{''}}</el-radio>
            <el-radio class="radio" label=2>{{''}}</el-radio>
          </el-radio-group>
        </td>
        <td></td>
      </tr>
      <tr>
        <td>教学效果</td>
        <td>达到了教学目的；教学内容能当堂消化巩固，教学效果好</td>
        <td>基本上能达到教学目的，有较多的缺点和错误，教学内容均能听懂但未能当堂消化，教学效果一般</td>
        <td>5</td>
        <td colspan="4">
          <el-radio-group v-model="radioList.radio14" @change="selectRadio13">
            <el-radio class="radio" label=5>{{''}}</el-radio>
            <el-radio class="radio" label=4>{{''}}</el-radio>
            <el-radio class="radio" label=3>{{''}}</el-radio>
            <el-radio class="radio" label=2>{{''}}</el-radio>
          </el-radio-group>
        </td>
        <td></td>
      </tr>
      <tr>
        <td>见习报告(10%)</td>
        <td></td>
        <td></td>
        <td>能够对所参加的见习活动做出全面、详细的总结与反思，包括课堂教学、班级管理和教育研究等各个方面，并且在这个过程中有深入的思考</td>
        <td>能够对所参加见习活动进行基本总结，但内容不全面，见解不深入</td>
        <td>10</td>
        <td colspan="4">
          <el-radio-group v-model="radioList.radio15" @change="selectRadio14">
            <el-radio class="radio" label=10>{{''}}</el-radio>
            <el-radio class="radio" label=8>{{''}}</el-radio>
            <el-radio class="radio" label=6>{{''}}</el-radio>
            <el-radio class="radio" label=4>{{''}}</el-radio>
          </el-radio-group>
        </td>
        <td></td>
      </tr>
      </tbody>
<!--      表脚-->
      <tfoot>
      <tr>
        <td style="color:firebrick;font-weight: bolder;font-size: 16px;">
          合计总分
        </td>
        <td  colspan="10">{{this.totalMark}}</td>
      </tr>
      </tfoot>
    </table>
      <span style="font-weight: bolder">注：评估时，低于A级高于C级的打B，低于C级的打D。评估等级中A、B、C、D分别为权重乘以1.0、0.8、0.6、0.4</span>
    </div>
    <div class="actions">
      <el-button type="primary" style="background-color: grey;margin-right:100px;cursor: pointer" @click="resetMark">重置分数</el-button>
      <el-button type="primary" style="background-color: #5090e2;;margin-left:100px;cursor: pointer" @click="submitGrade">确定</el-button>
    </div>
  </div>
</template>

<script>
    export default {
        name: "gradeScaler",
        data(){
            return{
               checkValue:[],
               username:'',//所评价学生的用户名
               radioList:{
                   radio1:'1',
                   radio2:'1',
                   radio3:'1',
                   radio4:'1',
                   radio5:'1',
                   radio6:'1',
                   radio7:'1',
                   radio8:'1',
                   radio9:'1',
                   radio10:'1',
                   radio11:'1',
                   radio12:'1',
                   radio13:'1',
                   radio14:'1',
                   radio15:'1'
                },
                markList:{
                   mark1:0,
                   mark2:0,
                   mark3:0,
                   mark4:0,
                   mark5:0,
                   mark6:0,
                   mark7:0,
                   mark8:0,
                   mark9:0,
                   mark10:0,
                   mark11:0,
                   mark12:0,
                   mark13:0,
                   mark14:0,
                   mark15:0
                },
                totalMark:0
            }
        },
        methods:{
            resetMark(){
               //重置分数
                this.$confirm('确定要重置分数吗?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    that.totalMark=0
                    that.radioList.radio1='1'
                    that.radioList.radio2='1'
                    that.radioList.radio3='1'
                    that.radioList.radio4='1'
                    that.radioList.radio5='1'
                    that.radioList.radio6='1'
                    that.radioList.radio7='1'
                    that.radioList.radio8='1'
                    that.radioList.radio9='1'
                    that.radioList.radio10='1'
                    that.radioList.radio11='1'
                    that.radioList.radio12='1'
                    that.radioList.radio13='1'
                    that.radioList.radio14='1'
                    that.radioList.radio15='1'
                }).catch((err)=>{
                    console.log(err)
                })

            },
            backone(){
              //返回上一页
                this.$router.go(-1)
            },
            selectRadio(data){
                //单选框被选中事件
                console.log(Number(data))
                this.markList.mark1=Number(data)
                this.totalMark=this.totalMark+this.markList.mark1
            },
            selectRadio1(data){
                //单选框被选中事件
                console.log(Number(data))
                this.markList.mark2=Number(data)
                this.totalMark=this.totalMark+this.markList.mark2
            },
            selectRadio2(data){
                //单选框被选中事件
                console.log(Number(data))
                this.markList.mark3=Number(data)
                this.totalMark=this.totalMark+this.markList.mark3
            },
            selectRadio3(data){
                //单选框被选中事件
                console.log(Number(data))
                this.markList.mark4=Number(data)
                this.totalMark=this.totalMark+this.markList.mark4
            },
            selectRadio4(data){
                //单选框被选中事件
                console.log(Number(data))
                this.markList.mark5=Number(data)
                this.totalMark=this.totalMark+this.markList.mark5
            },
            selectRadio5(data){
                //单选框被选中事件
                console.log(Number(data))
                this.markList.mark6=Number(data)
                this.totalMark=this.totalMark+this.markList.mark6
            },
            selectRadio6(data){
                //单选框被选中事件
                console.log(Number(data))
                this.markList.mark7=Number(data)
                this.totalMark=this.totalMark+this.markList.mark7
            },
            selectRadio7(data){
                //单选框被选中事件
                console.log(Number(data))
                this.markList.mark8=Number(data)
                this.totalMark=this.totalMark+this.markList.mark8
            },
            selectRadio8(data){
                //单选框被选中事件
                console.log(Number(data))
                this.markList.mark9=Number(data)
                this.totalMark=this.totalMark+this.markList.mark9
            },
            selectRadio9(data){
                //单选框被选中事件
                console.log(Number(data))
                this.markList.mark10=Number(data)
                this.totalMark=this.totalMark+this.markList.mark10
            },
            selectRadio10(data){
                //单选框被选中事件
                console.log(Number(data))
                this.markList.mark11=Number(data)
                this.totalMark=this.totalMark+this.markList.mark11
            },
            selectRadio11(data){
                //单选框被选中事件
                console.log(Number(data))
                this.markList.mark12=Number(data)
                this.totalMark=this.totalMark+this.markList.mark12
            },
            selectRadio12(data){
                //单选框被选中事件
                console.log(Number(data))
                this.markList.mark13=Number(data)
                this.totalMark=this.totalMark+this.markList.mark13
            },
            selectRadio13(data){
                //单选框被选中事件
                console.log(Number(data))
                this.markList.mark14=Number(data)
                this.totalMark=this.totalMark+this.markList.mark14
            },
            selectRadio14(data){
                //单选框被选中事件
                console.log(Number(data))
                this.markList.mark15=Number(data)
                this.totalMark=this.totalMark+this.markList.mark15
            },
            submitGrade(){
                //提交成绩，存到两个表中，一个细节表，一个总表
                let that= this
                that.$http.post('/yii/evaluation/mark/submitgrade',{
                    username:that.username,
                    total:that.totalMark,//总分
                    mark1:that.markList.mark1+that.markList.mark2+that.markList.mark3,//行为表率
                    mark2:that.markList.mark4+that.markList.mark5+that.markList.mark6,//课堂教学见习
                    mark3:that.markList.mark7,//班级管理
                    mark4:that.markList.mark8,//教研活动
                    mark5:that.markList.mark9+that.markList.mark10+that.markList.mark11+that.markList.mark12+that.markList.mark13+that.markList.mark14,//试讲
                    mark6:that.markList.mark15//见习报告
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        this.$message({
                            message: '分数提交成功！',
                            type: 'success'
                        });
                    }
                })
            }
        },
        created() {
            this.username=this.$route.params.username
            console.log(this.username)
        }
    }
</script>

<style scoped="scoped" type="text/css">
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
    background-color: #edebe2;
    text-align: center;
    position:-webkit-sticky;
    position:sticky;
    /*width:30px;*/
  }
  td{
    border: solid 1px #ccc;
    padding: 5px;
    text-align: center;
    font-size: 14px;
    word-wrap: break-word;
    /*width:30px;*/
  }
  .actions{
    padding-top: 20px;
  }
</style>
