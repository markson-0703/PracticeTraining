<template>
  <div class="display">
  <span style="margin-top:10px;margin-left:20px;color:#337ab7;font-weight: bolder;float:left;">
    <i class="el-icon-s-comment"></i>
    消息中心 Message Center
  </span>
    <div class="notice" v-if="this.showDiv==true">
      <div style="cursor: pointer;margin:0px">
      <span @click="labelAll()" class="noticeLabel">全部标为已读</span>
      </div>
      <el-tabs v-model="activeName" @tab-click="handleClick" style="margin-top: 10px">
        <el-tab-pane :label="'未读 ('+notRead+')'" name="first">
          <div class="originSet" v-if="this.notRead==0">
            <img src="../../../common/images/notice.jpg">
          </div>
          <div class="showNotice" v-if="this.notRead!=0">
            <table id="noticeStastics">
              <tr>
                <th style="width:10%">序号</th>
                <th style="width:50%">内容</th>
                <th style="width:25%">发布时间</th>
                <th style="width:15%">已读/未读</th>
              </tr>
              <tr v-for="item in notreadList">
                <td>{{item.index+1}}</td>
                <td>{{item.content}}</td>
                <td>{{item.pushTime}}</td>
                <td>
                  <el-switch
                    v-model="item.status"
                    active-color="#13ce66"
                    inactive-color="#ff4949"
                    active-text="已读"
                    :active-value="1"
                    :inactive-value="0"
                    @change="changeState($event,item.nId)">
                  </el-switch>
                </td>
              </tr>
            </table>
          </div>
        </el-tab-pane>
        <el-tab-pane :label="'全部消息 ('+haveRead+')'" name="second">
          <div class="originSet" v-if="this.haveRead==0">
            <img src="../../../common/images/notice.jpg">
          </div>
          <div class="showNotice" v-if="this.haveRead!=0">
            <table id="noticeStastics1">
              <tr>
                <th style="width:10%">序号</th>
                <th style="width:50%">内容</th>
                <th style="width:25%">发布时间</th>
                <th style="width:15%">状态</th>
              </tr>
              <tr v-for="item in havereadList">
                <td>{{item.index+1}}</td>
                <td>{{item.content}}</td>
                <td>{{item.pushTime}}</td>
                <td>
                  <el-tag type="danger" dark>已读</el-tag>
                </td>
              </tr>
            </table>
          </div>
        </el-tab-pane>
      </el-tabs>
    </div>
  </div>
</template>

<script>
    export default {
        name: "noticeDetail",
        data(){
            return{
                activeName: 'first',
                notRead:0,//未读消息数目
                showDiv:false,
                haveRead:0,//已读消息数目
                notreadList:[],
                havereadList:[]
            }
        },
        methods:{
            handleClick(tab, event) {
                console.log(tab, event);
            },
            labelAll(){
                this.$confirm('确定将所有消息标为已读, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(()=>{
                    let that = this
                    that.$http.post('/yii/notice/notice/labelnotice2',{
                        username:that.username,
                        ojbect:4
                    }).then(function(res){
                        console.log(res.data)
                        if(res.data.message=="success"){
                            this.$message({
                                showClose: true,
                                message: '成功将所有消息设置为已读！',
                                type: 'success'
                            });
                            this.tutNotice()
                            this.readNotice()
                        }
                    })
                }).catch((err)=>{
                    console.log(err)
                })
            },
            tutNotice(){
                let that = this
                that.$http.post('/yii/notice/notice/gettutnotice',{
                    username:that.username,
                    ojbect:4
                }).then(function(res){
                    console.log(res.data)
                    that.notreadList=res.data.data
                    that.notRead=Object.keys(that.notreadList).length
                })
            },
            readNotice(){
                //获取校外教师的已读消息数据
                let that = this
                that.$http.post('/yii/notice/notice/gettutread',{
                    username:that.username,
                    ojbect:4
                }).then(function(res){
                    console.log(res.data)
                    that.havereadList=res.data.data
                    that.haveRead=Object.keys(that.havereadList).length
                })
            },
            changeState(data,id){
                console.log(data,id)//data为1则已读，data为0未读
                if(data==1) {
                    let that = this
                    that.$http.post('/yii/notice/notice/changestate2', {
                        nId: id,
                        username: that.username
                    }).then(function (res) {
                        console.log(res.data)
                        if(res.data.message=="success"){
                            //更新未读的状态
                            this.tutNotice()
                            this.readNotice()
                        }
                    })
                }else{
                    this.$notify.error({
                        title: '错误',
                        message: '错误操作！'
                    });
                }
            }
        },
        created() {
            this.username=this.$store.getters.getsName//用户名
            this.tutNotice()
            this.readNotice()
            setTimeout(()=>{
                this.showDiv=true
            },1000)
        }
    }
</script>

<style scoped="scoped" type="text/css">
  .display{
    width: 100%;
    height:800px;
    background-color:#edecec;
    position:relative;
    text-align: center;
  }
  .notice{
    width:90%;
    height:80%;
    display: inline-block;
    margin-top:30px;
    padding:10px;
    background-color: white;
  }
  .notice el-tabs__active-bar{
    background-color: #ff7540
  }
  .originSet{
    margin-top: 50px;
  }
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
    background-color: #dfe3e9;
    text-align: center;
    position:-webkit-sticky;
    position:sticky;
    width:30px;
  }
  td{
    border: solid 1px #ccc;
    padding: 5px;
    text-align: center;
    font-size: 14px;
    word-wrap: break-word;
  }
  .noticeLabel{
    float: right;
    color:darkgreen;
    font-size: 16px;
    position: relative;
    margin-right:20px;
    cursor: pointer
  }
</style>
