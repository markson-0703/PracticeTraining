<template>
<div class="mainContent">
<span>
  <p>以下为班主任实习工作部分的文件模板</p>
</span>
  <div class="template">
  <table id="sometemplates">
    <tr>
      <th>序号</th>
      <th>模板文件</th>
      <th>发布时间</th>
      <th>下载</th>
    </tr>
    <tr v-for="(item,index) in templates">
      <td>{{index+1}}</td>
      <td>{{item.filename}}</td>
      <td>{{item.publishTime}}</td>
      <td>
        <span style="cursor: pointer" @click="downloadTem(item.kind)">
              <el-button type="primary" icon="el-icon-download" circle></el-button>
            </span>
      </td>
    </tr>
  </table>
  </div>
</div>
</template>

<script>
    export default {
        name: "someTemplate",
        data(){
            return{
            templates:[]
            }
        },
        methods:{
          getData(){
              let that = this
              that.$http.post('/yii/template/mould/sometemplate',{
                  type:2,
                  status:1
              }).then(function(res){
                  console.log(res.data)
                  that.templates=res.data.data
              })
          },
            downloadTem(kind){
              //模板下载
                let that = this
                that.$http.post('/yii/template/mould/downtemplate',{
                    type:2,//实践类型
                    status:1,//学生用模板
                    kind:kind
                }).then(function(res){
                    console.log(res.data)
                    var originUrl='http://127.0.0.1/'+res.data.data//要预览文件的访问地址
                    window.open(originUrl)
                })
            }
        },
        created() {
            this.getData()
        }
    }
</script>
<style scoped="scoped" type="text/css">
  @import '../../../common/css/templateTable.css';
p{
  color:#1ab2ff;
  font-weight: bolder;
  font-size: 20px;
}
</style>
