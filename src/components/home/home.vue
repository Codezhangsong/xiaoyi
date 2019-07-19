<template>
    <div>
        <h5 class="mb-4">
            <i class="fas fa-map-marker-alt mr"></i>
            <span class="c6B78DE">首页</span>
        </h5>
        <!--机构-->
        <div class="row z_overh">
            <div class="col-sm-6 col-xl-3 col-md-3 z_pl0">
                <b-card class="mb-4 z_home z_home_bg1">
                    <div class="align-items-center">
                        <div class="z_home_list">
                            <p>家长总数</p>
                            <h3>{{generalInfo.total_parents?generalInfo.total_parents:0}}</h3>
                            <p>{{generalInfo.increase_parents?generalInfo.increase_parents:0}}<img src="../../assets/jtx.png" alt=""></p>
                        </div>
                        <img src="../../assets/home_list1.png" class="bg-img">
                    </div>
                </b-card>
            </div>
            <div class="col-sm-6 col-xl-3 col-md-3">
                <b-card class="mb-4 z_home z_home_bg2">
                    <div class="align-items-center">
                        <div class="z_home_list">
                            <p>学生总数</p>
                            <h3>{{generalInfo.total_students?generalInfo.total_students:0}}</h3>
                            <p>{{generalInfo.increase_students?generalInfo.increase_students:0}}<img src="../../assets/jtx.png" alt=""></p>
                        </div>
                        <img src="../../assets/home_list2.png" class="bg-img">
                    </div>
                </b-card>
            </div>
            <div class="col-sm-6 col-xl-3 col-md-3">
                <b-card class="mb-4 z_home z_home_bg3">
                    <div class="align-items-center">
                        <div class="z_home_list">
                            <p>课程总数</p>
                            <h3>{{generalInfo.course_total?generalInfo.course_total:0}}</h3>
                            <p>{{generalInfo.online_course?generalInfo.online_course:0}}<img src="../../assets/jtx.png" alt=""></p>
                        </div>
                        <img src="../../assets/home_list3.png" class="bg-img">
                    </div>
                </b-card>
            </div>
            <div class="col-sm-6 col-xl-3 col-md-3">
                <b-card class="mb-4 z_home z_home_bg4">
                    <div class="align-items-center">
                        <div class="z_home_list">
                            <p>活动总数</p>
                            <h3>{{generalInfo.activity_total?generalInfo.activity_total:0}}</h3>
                            <p>{{generalInfo.increase_activity?generalInfo.increase_activity:0}}<img src="../../assets/jts.png" alt=""></p>
                        </div>
                        <img src="../../assets/home_list4.png" class="bg-img">
                    </div>
                </b-card>
            </div>
        </div>

        <div class="row" >
            <div class="col-sm-12 col-xl-6 col-md-6 z_cplr" >
                <b-card class="mb-4 z_home_part3" >
                    <div class="row z_flex z_boder">
                        <h5 class="font-weight-bold mb-4">活动详情</h5>
                        <div class="z_for ">
                            <ul>
                                <li :class="{active:displayRange2===1}" @click="searchActivityDetail(1)">今日</li>
                                <li :class="{active:displayRange2===2}" @click="searchActivityDetail(2)">昨日</li>
                                <li :class="{active:displayRange2===3}" @click="searchActivityDetail(3)">近七日</li>
                                <li :class="{active:displayRange2===4}" @click="searchActivityDetail(4)">近30日</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row z_mt20">
                        <div class="col-sm-12 col-xl-12 z_home_part_chart clearPaddingMargin" id="homeChart">
                        </div>
                    </div>
                </b-card>
            </div>
            <div class="col-sm-12 col-xl-6 col-md-6 z_pr0" id="z_mb">
                <b-card class="mb-4 z_home_part3 z_sj_echarts">
                    <div class="row z_flex z_boder">
                        <h5 class="font-weight-bold mb-4">数据趋势</h5>
                        <div class="z_for">
                            <ul>
                                <li :class="{active:displayRange===1}" @click=" searchActivityDataDetail(1)">今日</li>
                                <li :class="{active:displayRange===2}" @click=" searchActivityDataDetail(2)">昨日</li>
                                <li :class="{active:displayRange===3}" @click=" searchActivityDataDetail(3)">近七日</li>
                                <li :class="{active:displayRange===4}" @click=" searchActivityDataDetail(4)">近30日</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row z_mt20">
                        <div class="col-sm-12 col-xl-4 col-md-4" id="doughnutChart1" style="width: 100%;height: 115px">
                        </div>
                        <div class="position-absolute z_home_bfb">
                            <p>{{searchR.joinRate}}%</p>
                            <p>参与率</p>
                        </div>
                        <div class="col-sm-6 col-xl-4  col-xs-6 z_sj_echarts_num col-md-4 ">
                            <P class="clearPaddingMargin">报名人数</P>
                            <P class="clearPaddingMargin">{{searchR.activityDetail.sign_up?searchR.activityDetail.sign_up:0}}</P>
                        </div>
                        <div class="col-sm-6 col-xl-4 col-xs-6 z_sj_echarts_num col-md-4">
                            <P class="clearPaddingMargin">参加人数</P>
                            <P class="clearPaddingMargin">{{searchR.activityDetail.join_num?searchR.activityDetail.join_num:0}}</P>
                        </div>
                    </div>
                </b-card>
                <b-card class="mb-4 clearPaddingMargin z_sj z_sjimg">
                    <div class="row">
                        <div class="col-sm-4 col-xl-4 mb-4 z_sjimgbox z_pr0">
                            <div class="z_sj_box">
                                <div class="z_sj_box_top">
                                    <p>PV数据</p>
                                    <img src="../../assets/jtx.png" alt="">
                                </div>
                                <div class="z_sj_box_cent">
                                    <p>{{searchR.activityDetail.total_PV?searchR.activityDetail.total_PV:0}}<span>次</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xl-4 mb-4 z_sjimgbox z_pr0">
                            <div class="z_sj_box">
                                <div class="z_sj_box_top">
                                    <p>UV数据</p>
                                    <img src="../../assets/jtx.png" alt="">
                                </div>
                                <div class="z_sj_box_cent">
                                    <p>{{searchR.activityDetail.total_UV?searchR.activityDetail.total_UV:0}}<span>次</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xl-4 mb-4 z_sjimgbox">
                            <div class="z_sj_box">
                                <div class="z_sj_box_top">
                                    <p>IP数据</p>
                                    <img src="../../assets/jts.png" alt="">
                                </div>
                                <div class="z_sj_box_cent">
                                    <p>{{searchR.activityDetail.total_IP?searchR.activityDetail.total_IP:0}}<span>个</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-card>
            </div>
        </div>

        <!--事物-->
        <div class="row" style="margin-top: -8px;">
            <div class="col-sm-12 col-xl-12 col-md z_cplr" >
                <b-card class="mb-4 z_home_part3">
                    <h5 class="font-weight-bold py-3 mb-4" style="border-bottom: 1px solid #ECECEC;">
                        <img src="../../assets/z_home_cl.png" width="30" height="30px" style="margin-top: -5px;">待处理事物
                    </h5>
                    <div class="row z_home_cl">
                        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 clearPaddingMargin">
                            <img src="../../assets/z_cl.png" class="z_zsy1">
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-10 col-lg-10 clearPaddingMargin row z_zsy1">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 ">
                                <div class="z_home_clnews">
                                    <div class="z_home_clnews_list">
                                        <img src="../../assets/z_sh1.png">
                                        待审核课程
                                    </div>
                                    <div class="z_home_clnews_num">
                                        {{courseGeneral.audit_course?courseGeneral.audit_course:0}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 ">
                                <div class="z_home_clnews ">
                                    <div class="z_home_clnews_list">
                                        <img src="../../assets/z_sh5.png">
                                        已通过课程
                                    </div>
                                    <div class="z_home_clnews_num">
                                        {{courseGeneral.online?courseGeneral.online:0}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 ">
                                <div class="z_home_clnews">
                                    <div class="z_home_clnews_list">
                                        <img src="../../assets/z_sh3.png">
                                        未通过课程
                                    </div>
                                    <div class="z_home_clnews_num z_home_clnews_color">
                                        {{courseGeneral.not_pass_course?courseGeneral.not_pass_course:0}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 ">
                                <div class="z_home_clnews">
                                    <div class="z_home_clnews_list">
                                        <img src="../../assets/z_sh2.png">
                                        待发布活动
                                    </div>
                                    <div class="z_home_clnews_num">
                                        {{activityGeneral.audit_activity?activityGeneral.audit_activity:0}}
                                    </div>
                                </div>
                            </div>
                         <!--   <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 ">
                                <div class="z_home_clnews">
                                    <div class="z_home_clnews_list">
                                        <img src="../../assets/z_sh4.png">
                                        已下架活动
                                    </div>
                                    <div class="z_home_clnews_num  z_home_clnews_color">
                                        {{activityGeneral.offline_activity?activityGeneral.offline_activity:0}}
                                    </div>
                                </div>
                            </div>-->
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 ">
                                <div class="z_home_clnews">
                                    <div class="z_home_clnews_list">
                                        <img src="../../assets/z_sh6.png">
                                        已发布活动
                                    </div>
                                    <div class="z_home_clnews_num">
                                        {{activityGeneral.online_course?activityGeneral.online_course:0}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-card>
            </div>
        </div>

        <!--活动-->
        <div class="row">
            <div class="col-sm-6 col-xl-6 z_cplr">
                <b-card class="mb-4 z_home_part3 z_cplr">
                   <div class="row z_home_tab z_cplr">
                       <h5 class="font-weight-bold py-3 z_cplr mb-4 col-xs-12 col-sm-12 col-md-2 col-lg-3">
                           <img src="../../assets/z_home_zb.png" height="20px" width="20px" style="margin-right: 10px;">直播课
                       </h5>
                       <div class="z_home_tabtop row col-xs-12 col-sm-12 col-md-10 col-lg-9">
                           <ul class="tab-tilte col-xs-11  col-lg-12 col-md-10 col-sm-11">
                               <li @click="searchCourse(1)" :class="{active2:cur==1}">审核中<span v-if="cur==1">({{courseDataCount}})</span></li>
                               <li @click="searchCourse(3)" :class="{active2:cur==3}">已通过<span v-if="cur==3">({{courseDataCount}})</span></li>
                               <li @click="searchCourse(2)" :class="{active2:cur==2}">未通过<span v-if="cur==2">({{courseDataCount}})</span></li>
                           </ul>
                           <!--<p class="z_new_more">更多</p>-->
                       </div>
                   </div>

                    <s></s>
                    <div class="tab-content">
                        <div v-show="cur==1" v-for="(item,index) in courseData">
                            <div class="tab-content-box">
                                <div class="tab-content-boxnews">{{item.course_name}}</div>
                                <div class="tab-content-boxdata">{{item.created_at}}</div>
                            </div>
                        </div>
                        <div v-show="cur==3" v-for="(item,index) in courseData">
                            <div class="tab-content-box">
                                <div class="tab-content-boxnews">{{item.course_name}}</div>
                                <div class="tab-content-boxdata">{{item.created_at}}</div>
                            </div>
                        </div>
                        <div v-show="cur==2" v-for="(item,index) in courseData">
                            <div class="tab-content-box">
                                <div class="tab-content-boxnews">{{item.course_name}}</div>
                                <div class="tab-content-boxdata">{{item.created_at}}</div>
                            </div>
                        </div>
                    </div>
                </b-card>
            </div>
            <div class="col-sm-6 col-xl-6 z_pr0 ">
                <b-card class="mb-4 z_home_part3 z_cplr">
                    <div class="row z_home_tab ">
                        <h5 class="font-weight-bold z_cplr py-3 mb-4 col-sm-12  col-lg-3 ">
                            <img src="../../assets/z_home_hd.png" height="20px" width="20px" style="margin-right: 10px;">活动
                        </h5>
                        <div class="z_home_tabtop row col-sm-12 col-lg-9 z_pr0">
                            <ul class="tab-tilte  col-lg-8   col-md-6 col-sm-11" >
                                <li @click="searchActivity(1)" :class="{active2:cur2==1}">待发布<span v-if="cur2==1">({{activityDataCount}})</span></li>
                                <li @click="searchActivity(3)" :class="{active2:cur2==3}">未发布<span v-if="cur2==3">({{activityDataCount}})</span></li>
                                <!-- <li @click="searchActivity(2)" :class="{active:cur2==2}">已审核<span v-if="cur2==2">({{activityDataCount}})</span></li>-->
                            </ul>

                            <!--<p class="">更多</p>-->
                        </div>
                    </div>
                    <s></s>
                    <div class="tab-content" >
                        <div v-show="cur2==1" v-for="(item,index) in activityData">
                            <div class="tab-content-box">
                                <div class="tab-content-boxnews">{{item.name}}</div>
                                <div class="tab-content-boxdata">{{item.created_at}}</div>
                            </div>
                        </div>
                        <div v-show="cur2==3" v-for="(item,index) in activityData">
                            <div class="tab-content-box">
                                <div class="tab-content-boxnews">{{item.name}}</div>
                                <div class="tab-content-boxdata">{{item.created_at}}</div>
                            </div>
                        </div>
                        <!--<div v-show="cur2==2" v-for="(item,index) in activityData">
                            <div class="tab-content-box">
                                <div class="tab-content-boxnews">{{item.name}}</div>
                                <div class="tab-content-boxdata">{{item.created_at}}</div>
                            </div>
                        </div>-->
                    </div>
                </b-card>
            </div>
        </div>
    </div>
</template>
<script>
/* eslint-disable object-curly-spacing */

export default {
  name: 'eCharts',
  data: function () {
    return {
      /* msg: 'Welcome to Your Vue.js App', */
      tabIndex: 0,
      displayRange: 3,
      displayRange2: 1,
      cur:1,
      cur2:1,
      tabChartData: [
        {title: '营销活动|粉丝排行'},
        {title: '直播课|粉丝排行'}
      ],
      chartsData: {
        products: ['新增活动', '同期新增活动'],
        data: [
          {
            release: [0],
            fans: [0]
          },
          {
            release: [0],
            fans: [0]
          }
        ]
      },
      generalInfo: {},
      courseGeneral: {},
      activityGeneral: {},
      searchR: {
        activityDetail: {},
        joinRate: 0
      },
      xDataCreate: {},
      xData: [],
      courseData: {},
      courseDataCount: 0,
      activityData: {},
      activityDataCount: 0
    }
  },
  mounted () {
    this.drawLine(this.tabIndex)
    this.doughnutChart1()
  },
  methods: {

    

    
    tabChart (index) {
      this.tabIndex = index
      this.drawLine(index)
    },

    drawLine (index) {
      // 基于准备好的dom，初始化echarts实例
      var myChart = this.$echarts.init(document.getElementById('homeChart'))
      // 绘制图表
      myChart.setOption({
        // 图标提示样式
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'cross',
            crossStyle: {
              color: '#999'
            }
          }
        },
        // 下方图例
        legend: {
          icon: 'circle', // 图例形状
          bottom: 10, // 距离底部距离
          itemGap: 68, // 图例间距
          data: this.chartsData.products, // 图例名称，有多少种类显示多少个
          textStyle: {
            color: '#666666'
          }
        },
        grid: {
          top: 20,
          x: 30,
          y: 0,
          x2: 30,
          y2: 80
        },
        // X轴设置
        xAxis: [
          {
            type: 'category',
            data: this.xData,

            axisPointer: {
              type: 'shadow'
            },
            // X轴分割刻度
            axisTick: {
              show: false
            },
            // X轴字体颜色
            axisLine: {
              lineStyle: {
                color: '#C1C4C8'
              }
            }
          }
        ],
        yAxis: [
          {
            splitLine: {
              lineStyle: {
                color: '#C1C4C8'
              }
            },
            axisTick: {
              show: false
            },
            type: 'value',
            axisLabel: {
              formatter: '{value}'
            },
            axisLine: {
              show: false,
              lineStyle: {
                color: '#C1C4C8'
              }
            }
          }
        ],
        // 展示数据模块
        series: [
          {
            type: 'bar', // 图标类型，此处是柱壮图
            name: this.chartsData.products[0], // 数据名称
            barWidth: 29, // 柱子宽度
            grid: {
              top: 20,
              x: 30,
              y: 0,
              x2: 30,
              y2: 80
            },
            color: {// 柱子颜色设置
              type: 'linear',
              x: 0,
              y: 0,
              x2: 0,
              y2: 1,
              colorStops: [{
                offset: 0, color: '#C3B6F7' // 0% 处的颜色
              }, {
                offset: 1, color: '#957EEF' // 100% 处的颜色
              }],
              global: false // 缺省为 false
            },
            data: this.chartsData.data[index].release // 要放置的数据
          },
          {
            type: 'bar',
            name: this.chartsData.products[1],
            barWidth: 29,
            color: {
              type: 'linear',
              x: 0,
              y: 0,
              x2: 0,
              y2: 1,
              colorStops: [{
                offset: 0, color: '#9CF2B1' // 0% 处的颜色
              }, {
                offset: 1, color: '#5DCECD' // 100% 处的颜色
              }],
              global: false // 缺省为 false
            },
            data: this.chartsData.data[index].fans
          }
        ]
      })
    },
    doughnutChart1 () {
      var myChart = this.$echarts.init(document.getElementById('doughnutChart1'))
      myChart.setOption(
        {
          color: [
            {
              type: 'linear',
              colorStops: [{
                offset: 0, color: '#FF6EA2' // 0% 处的颜色
              }, {
                offset: 1, color: '#FFBD8C' // 100% 处的颜色
              }]
            },
            {

              colorStops: [{
                offset: 0, color: '#9863CD' // 0% 处的颜色
              }, {
                offset: 0.25, color: '#693FBD' // 100% 处的颜色
              }, {
                offset: 0.75, color: '#693FBD' // 100% 处的颜色
              }, {
                offset: 1, color: '#9863CD' // 100% 处的颜色
              }]
            }
          ],
          tooltip: {
            show:false,
            trigger: 'item',
            formatter: '{a} <br/>{b}: {c} ({d}%)'
          },
          legend: {
            show:false
          },
          series: [
            {
              name:'访问来源',
              type:'pie',
              radius: ['70%', '100%'],
              avoidLabelOverlap: false,
              hoverAnimation:false,
              itemStyle:{
                borderWidth:5,
                borderColor: '#ffffff'
              },
              label: {
                normal: {
                  show: false,
                  position: 'center'
                },
                emphasis: {
                  show: true,
                  textStyle: {
                    fontSize: '16',
                    fontWeight: 'bold'
                  }
                }
              },
              labelLine: {
                normal: {
                  show: false
                }
              },
              data:[
                {value:335},
                {value:310}
              ]
            }
          ]
        })
    },
    getParamsToken (paramsMd5) {
      var paramsEndData = {}
      var paramsMulArr = []
      var timeStamp = Math.round(new Date().getTime() / 1000)
      paramsMd5['timeStamp'] = timeStamp
      for (let i in paramsMd5) {
        var o = { key: i, val: paramsMd5[i] }
        paramsMulArr.push(o)
      }
      paramsMulArr.sort()
      var str = '' // app_key: 'edutage520'
      paramsMulArr.forEach((item, index, arr) => {
        str += index + '' + item['val']
        paramsEndData[item['key']] = item['val']
      })
      str += `${this.appTokenKey}`
      var token = this.$md5(str)
      paramsEndData['token'] = token
      return paramsEndData
    },
    searchActivityDetail (dateType) {
      var that = this
      that.displayRange2 = dateType
      let postData = {
        dateType: dateType
      }
      that.axios.post(`${this.devUrl}stat/activity/trend`, that.getParamsToken(postData))
      // that.axios.get(`${that.baseUrl}json/pages_users_list.json`)
        .then(function (response) {
          var data = response.data
          if (data.code === 200) {
            var createData = data.data
            var createNewData = [] // 新增数据
            var sameTimeData = [] // 同期新增数据
            var createXZData = []
            for (var i = 0; i < createData.length; i++) {
              createNewData.push(createData[i]['total_number'])
              sameTimeData.push(createData[i]['last_year_num'])
              var changeXdatas = ''
              if (dateType === 1 || dateType === 2) {
                changeXdatas = createData[i]['created_at']
              } else if (dateType === 3 || dateType === 4) {
                changeXdatas = createData[i]['date']
              } else if (dateType === 5) {
                changeXdatas = that.getMonth(createData[i]['month'])
              }
              createXZData.push(changeXdatas)
            }
            that.chartsData.data[0].release = createNewData // 第一个柱状图
            that.chartsData.data[0].fans = sameTimeData // 第二个柱状图
            that.xData = createXZData // x轴0
            console.log(createXZData)
          } else {
            that.chartsData.data[0].release = [0] // 第一个柱状图
            that.chartsData.data[0].fans = [0] // 第二个柱状图
          }
          that.drawLine(0)
        })
    },
    searchActivityDataDetail (dateType) {
      var that = this
      that.displayRange = dateType
      let postData = {
        dateType: dateType
      }
      that.axios.post(`${this.devUrl}index/activity/detail`, that.getParamsToken(postData))
        .then(function (response) {
          var data = response.data
          var datas = data.data
          if (data.code === 200) {
            that.searchR.activityDetail = datas.activity_detail
            that.searchR.joinRate = datas.join_rate
            return false
          }
        })
    },
    searchCourse (cur) {
      var that = this
      that.cur = cur
      let postData = {
        status: cur
      }
      that.axios.post(`${this.devUrl}course/search`, that.getParamsToken(postData))
        .then(function (response) {
          var data = response.data
          if (data.code === 200) {
            var datas = data.data
            that.courseData = datas.data
            that.courseDataCount = datas.count
            return false
          } else {
            that.courseData = {}
            that.courseDataCount = 0
          }
        })
    },
    searchActivity (cur2) {
      var that = this
      that.cur2 = cur2
      let postData = {
        status: cur2,
        limit: 4
      }
      that.axios.post(`${this.devUrl}activity/search`, that.getParamsToken(postData))
        .then(function (response) {
          var data = response.data
          if (data.code === 200) {
            var datas = data.data
            that.activityData = datas.data
            that.activityDataCount = datas.count
            return false
          } else {
            that.activityData = {}
            that.activityDataCount = 0
          }
        })
    },
    getWeek (day) {
      var weekArr = {
        1: '周一',
        2: '周二',
        3: '周三',
        4: '周四',
        5: '周五',
        6: '周六',
        7: '周日'
      }
      return weekArr[day]
    },
    fun(){
        console.log(123);
    },
    getMonth (month) {
      var monthArr = {
        1: 'Jan',
        2: 'Feb',
        3: 'Mar',
        4: 'Apr',
        5: 'May',
        6: 'Jun',
        7: 'Jul',
        8: 'Aug',
        9: 'Sep',
        10: 'Oct',
        11: 'Nev',
        12: 'Dec'
      }
      return monthArr[month]
    }
  },
  created () {

       this.startTraining();

    var that = this
    // 家长等级列表
    let postData = {}
    that.axios.post(`${this.devUrl}index`, that.getParamsToken(postData))
      .then(function (response) {
        var data = response.data
        var datas = data.data
        if (data.code === 200) {
          that.generalInfo = datas.general_info
          that.courseGeneral = datas.course_general
          that.activityGeneral = datas.activity_general
          // console.log(data)
          return false
        }
      })
    that.searchActivityDetail(1)  //活动详情数据
    that.searchActivityDataDetail(1)  //活动详情数据趋势
    that.searchCourse(that.cur)  //直播课数据
    that.searchActivity(that.cur2)  //直播课数据
  }
}
</script>
<style scoped>
    @import "./home.css";

</style>
<!--
created() {
  window.InitSetInterval = setInterval(fn(),2000)
},mounted() {
},
destroyed: {
   clearInterval(window.InitSetInterval )
}
-->