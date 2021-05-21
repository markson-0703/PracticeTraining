<template>
  <div class="display">
    <table id="memberStastics">
      <tr>
        <th>序号</th>
        <th>姓名</th>
        <th>学号</th>
        <th>校内导师</th>
        <th>见习年级</th>
        <th>状态</th>
      </tr>
      <tr v-for="(item,index) in memberList">
        <td>{{index+1}}</td>
        <td>{{item.sName}}</td>
        <td>{{item.sno}}</td>
        <td>{{item.tName}}</td>
        <td>{{item.grade}}</td>
        <td v-if="item.status==1">有效</td>
        <td v-if="item.status==0">已失效</td>
      </tr>
    </table>
    <el-tag  type="danger" effect="dark" style="float:left;margin-top: 30px">
      指导学生总数：{{this.total}}
    </el-tag>
  </div>
</template>

<script>
    export default {
        name: "memberData",
        data(){
            return{
                username:'',
                total:0,//实习指导学生个数
                memberList:[]
            }
        },
        methods:{
            allMember(){
                let that = this
                that.$http.post('/yii/practice/message/memberlist',{
                    username:that.username
                }).then(function(res){
                    console.log(res.data)
                    if(res.data.message=="success"){
                        that.memberList=res.data.data
                    }
                    that.total=that.memberList.length
                })
            }
        },
        created() {
            this.username=this.$store.getters.getsName
            this.allMember()
        }
    }
</script>

<style scoped="scoped" type="text/css">
  @import "../../../common/css/pagination.css";
</style>
