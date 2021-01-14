<template>
  <div style="width: 600px;height:400px;border:1px solid rgb(180,180,180);top: 0px" id="echartss">
  </div>
</template>

<script>
    export default {
        name: "BarChart",
        data(){
            return{
                Bx: [],
                By: [],
                Btitle: ''
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
        methods: {
            init: function () {
                this.drawBar()
            },
            ToData: function (data, t) {
                this.Btitle = t
                this.Bx = []
                this.By = []
                if (data != null) {
                    data.forEach((value, index) => {
                        this.Bx.push(value.name)
                        this.By.push(value.num)
                    })
                }
            },
            drawBar: function () {
                this.ToData(this.data, this.title)
                console.log(this.data)
                let myChart = this.$echarts.init(
                    document.getElementById('echartss')
                )
                myChart.setOption({
                    color: ['#3398D8'],
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        }
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
                    xAxis: [
                        {
                            type: 'category',
                            name: '教师姓名',
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
                            name: '记录数据',
                            scale:true,
                            max: 20,
                            min: 0,
                            splitNumber:5
                        }
                    ],
                    series: [
                        {
                            // name: this.Btitle,
                            type: 'bar',
                            color: ['#58afed'],
                            barWidth: '60%',
                            data: this.By,
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
