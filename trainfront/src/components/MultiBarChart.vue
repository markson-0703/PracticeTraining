<template>
  <div style="width: 600px;height:400px;border:1px solid rgb(180,180,180);top: 0px" id="mychart">
  </div>
</template>

<script>
    export default {
        name: "MultiBarChart",
        data(){
            return{
                Bx: [],
                By1: [],
                By2: [],
                By3: [],
                Btitle:''
            }
        },
        props: ['data', 'title'],
        mounted () {
            this.init()
        },
        watch: {
            x (oldVal, newVal) {
                this.drawBar()
            },
            y (oldVal, newVal) {
                this.drawBar()
            },
            title (oldVal, newVal) {
                this.drawBar()
            }
        },
        methods:{
            init: function () {
                this.drawBar()
            },
            ToData: function (data, t) {
                this.Btitle = t
                this.Bx = []
                this.By1 = []
                this.By2 = []
                this.By3 = []
                if (data != null) {
                    data.forEach((value, index) => {
                        this.Bx.push(value.name)
                        this.By1.push(value.num1)
                        this.By2.push(value.num2)
                        this.By3.push(value.num3)
                    })
                }
            },
            drawBar: function () {
                this.ToData(this.data, this.title)
                let myChart = this.$echarts.init(
                    document.getElementById('mychart')
                )
                myChart.setOption({
                    color: ['#3398D8'],
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        }
                    },
                    legend: {
                        data:['记录','文档','视频']
                    },
                    toolbox: { //可视化的工具箱
                        show: true,
                        feature: {
                            dataView: { //数据视图
                                show: true
                            },
                            restore: { //重置
                                show: true
                            },
                            dataZoom: { //数据缩放视图
                                show: true
                            },
                            saveAsImage: {//保存图片
                                show: true
                            },
                            magicType: {//动态类型切换
                                type: ['bar', 'line']
                            }
                        }
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        height: '80%',
                        width: '80%',
                        containLabel: true
                    },
                    calculable : true,
                    xAxis: [
                        {
                            type: 'category',
                            name: '学生姓名',
                            data: this.Bx,
                            axisLabel: {
                                show: true,
                                textStyle: {
                                    color: '#25252E'
                                }
                            },
                            axisTick: {
                                alignWithLabel: true
                            }
                        }
                    ],
                    yAxis: [
                        {
                            type: 'value',
                            name: '见习数据',
                            scale:true,
                            max: 20,
                            min: 0,
                            splitNumber:5
                        }
                    ],
                    series: [
                        {
                            type: 'bar',
                            name:'记录',
                            color: ['#CC0066'],
                            barWidth : 20,
                            data: this.By1,
                            label:{
                                normal:{
                                    show:true,
                                    position:'top',
                                    textStyle:{
                                        color:'black'
                                    }
                                }
                            }
                        },
                        {
                            type: 'bar',
                            name:'文档',
                            color: ['#009999'],
                            barWidth : 20,
                            data: this.By2,
                            label:{
                                normal:{
                                    show:true,
                                    position:'top',
                                    textStyle:{
                                        color:'black'
                                    }
                                }
                            }
                        },
                        {
                            type: 'bar',
                            name:'视频',
                            color: ['#FFCC33'],
                            barWidth : 20,
                            data: this.By3,
                            label:{
                                normal:{
                                    show:true,
                                    position:'top',
                                    textStyle:{
                                        color:'black'
                                    }
                                }
                            }
                        }

                    ]
                },true)
            }
        }
    }
</script>

<style scoped="scoped" type="text/css">

</style>
